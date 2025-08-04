<?php

/**
 * @file plugins/blocks/navigation/NavigationBlockPlugin.php
 *
 * Copyright (c) 2025- Simon Fraser University
 * Copyright (c) 2025- John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * Author: Martin Brändle, Swiss Chemical Society
 *
 * @class NavigationBlockPlugin
 * @brief Class for navigation block plugin
 */

namespace APP\plugins\blocks\navigation;

use APP\facades\Repo;
use APP\issue\Collector;
use APP\issue\Issue;
use APP\template\TemplateManager;
use PKP\cache\CacheManager;
use PKP\context\Context;
use PKP\plugins\BlockPlugin;
use PKP\submission\PKPSubmission;

class NavigationBlockPlugin extends BlockPlugin
{
    private const NAVIGATION_BLOCK_CACHE_DAYS = 1;
    private const ONE_DAY_SECONDS = 60 * 60 * 24;
    private const DAYS_SECONDS = self::ONE_DAY_SECONDS * self::NAVIGATION_BLOCK_CACHE_DAYS;

    /**
     * Register the plugin.
     *
     * @return string
     */
    public function register($category, $path, $mainContextId = NULL)
    {
        // Register the plugin even when it is not enabled
        $success = parent::register($category, $path);

        if ($success && $this->getEnabled()) {
            // Do something when the plugin is enabled
        }

        return $success;
    }

    /**
     * Get the display name of this plugin.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return __('plugins.block.navigation.displayName');
    }

    /**
     * Get a description of the plugin.
     */
    public function getDescription()
    {
        return __('plugins.block.navigation.description');
    }

    /**
     * Get the HTML contents of the navigation block.
     *
     * @param PKPTemplateManager $templateMgr
     * @param null|mixed $request
     *
     * @return string
     */
    public function getContents($templateMgr, $request = null)
    {
        $blockTemplateFilename = '';    

        $context = $request->getContext();
        if (!$context) {
            return '';
        }

        $router = $request->getRouter();
        $contextUrl = $request->getRouter()->url($request, $context->getPath());

        $pageop = $router->getRequestedPage($request) . '/' . $router->getRequestedOp($request);
        if ($pageop != 'issue/view' && $pageop != 'article/view' && $pageop != 'index/index' && $pageop != '/index') {
            return '';
        }

        $args = $router->getRequestedArgs($request);
        if (is_null($args[0]))
        {
            $args[0] = '';
        }

        $issue_ids = $this->getCachedIssues($context);

        $displayedIssue = $this->getDisplayedIssue($context, $pageop, $args[0]);
        $displayedIssueSeq = $this->getSequence($issue_ids, $displayedIssue->getId());

        $firstIssue = $this->getFirstIssue($context, $issue_ids);        

        $previousIssue = $this->getPreviousIssue($context, $issue_ids, $displayedIssueSeq);
        $nextIssue = $this->getNextIssue($context, $issue_ids, $displayedIssueSeq);

        $lastIssue = Repo::issue()->getCurrent($context->getId());
        if ($lastIssue->getId() == $displayedIssue->getId()) {
            $lastIssue = null;
        }

        if ($pageop == 'article/view') {
            $submission_ids = $this->getCachedSubmissions($context, $displayedIssue);

            if (!is_null($previousIssue)) {
                $previousIssueSubmissionIds = $this->getCachedSubmissions($context, $previousIssue);
                $previousIssueFirstArticle = $this->getFirstArticle($context, $previousIssueSubmissionIds, -1);
            }

            if (!is_null($nextIssue)) {
                $nextIssueSubmissionIds = $this->getCachedSubmissions($context, $nextIssue);
                $nextIssueFirstArticle = $this->getFirstArticle($context, $nextIssueSubmissionIds, -1);
            }
        
            $displayedSubmission = $this->getDisplayedSubmission($context, $pageop, $args[0]);
            $displayedSubmissionSeq = $this->getSequence($submission_ids, $displayedSubmission->getId());

            $firstArticle = $this->getFirstArticle($context, $submission_ids, $displayedSubmissionSeq);
            $previousArticle = $this->getPreviousArticle($context, $submission_ids, $displayedSubmissionSeq);
            $nextArticle = $this->getNextArticle($context, $submission_ids, $displayedSubmissionSeq);
            $lastArticle = $this->getLastArticle($context, $submission_ids, $displayedSubmissionSeq);
            $tocPath = $contextUrl . "/issue/view/" . $displayedIssue->getBestIssueId();

            $blockTemplateFilename = 'block_articlenavig.tpl';
            $templateMgr->assign([
                'contextUrl' => $contextUrl, 
                'displayedIssue' => $displayedIssue,
                'previousIssueArticle' => $previousIssueFirstArticle,
                'nextIssueArticle' => $nextIssueFirstArticle,
                'firstArticle' => $firstArticle,
                'previousArticle' => $previousArticle,
                'nextArticle' => $nextArticle,
                'lastArticle' => $lastArticle,
                'tocPath' => $tocPath,
            ]);
        }

        if ($pageop == 'issue/view' || $pageop == 'index/index' || $pageop == '/index') {
            $blockTemplateFilename = 'block_issuenavig.tpl';
            $templateMgr->assign([
                'contextUrl' => $contextUrl,
                'displayedIssue' => $displayedIssue,
                'firstIssue' => $firstIssue,
                'previousIssue' => $previousIssue,
                'nextIssue' => $nextIssue,
                'lastIssue' => $lastIssue,
            ]);
        }

        return $templateMgr->fetch($this->getTemplateResource($blockTemplateFilename));
    }

    /**
     * Dismiss the cache
     *
     * @return null
     */
    public function cacheDismiss()
    {
        return null;
    }

    /**
     * Get the ids of the sorted issues from the cache
     *
     * @param Context $context
     *
     * @return array $issue_ids
     */    
    private function getCachedIssues(Context $context): array
    {
        $cacheManager = CacheManager::getManager();
        $cache = $cacheManager->getFileCache(
            $context->getId(),
            'issue_ids',
            [$this, 'cacheDismiss']
        );

        $issue_ids = & $cache->getContents();
        $currentCacheTime = time() - $cache->getCacheTime();

        if (($issue_ids && $issue_ids != '[]') && $currentCacheTime < self::DAYS_SECONDS) {
            return $issue_ids;
        }

        if ($currentCacheTime > self::DAYS_SECONDS) {
            $cache->flush();
        }

        $cache->setEntireCache($this->getJournalIssues($context->getId()));
        $issue_ids = & $cache->getContents();

        return $issue_ids;
    }

    /**
     * Get the ids of the sorted issues of a journal
     *
     * @param int $journalId
     *  
     * @return array
     */
    private function getJournalIssues(int $journalId): array
    {
        $count = 1000;
        $offset = 0;

        $issue_ids = Repo::issue()->getCollector()
            ->limit($count)
            ->offset($offset)
            ->filterByContextIds([$journalId])
            ->orderBy(Collector::ORDERBY_SEQUENCE)
            ->filterByPublished(true)
            ->getIds()
            ->toArray();

        return $issue_ids;
    }

    /**
     * Get the cached ids of the sorted submissions for a given issue
     *
     * @param Context $context
     * @param Issue $issue
     * 
     * @return array
     */
    private function getCachedSubmissions(Context $context, Issue $issue): array
    {
        $cacheManager = CacheManager::getManager();
        $cache = $cacheManager->getFileCache(
            $context->getId(),
            'submission_ids_' . $issue->getId(),
            [$this, 'cacheDismiss']
        );

        $submission_ids = & $cache->getContents();
        $currentCacheTime = time() - $cache->getCacheTime();

        if (($submission_ids && $submission_ids != '[]') && $currentCacheTime < self::DAYS_SECONDS) {
            return $submission_ids;
        }

        if ($currentCacheTime > self::DAYS_SECONDS) {
            $cache->flush();
        }

        $cache->setEntireCache($this->getIssueSubmissions($issue));
        $submission_ids = & $cache->getContents();

        return $submission_ids;
    }

    /**
     * Get the ids of the sorted submissions for a given issue
     *
     * @param Issue $issue
     *
     * @return array 
     */
    private function getIssueSubmissions(Issue $issue): array
    {
        $allowedStatuses = [PKPSubmission::STATUS_PUBLISHED];
        if (!$issue->getPublished()) {
            $allowedStatuses[] = PKPSubmission::STATUS_SCHEDULED;
        }

        $submissions = Repo::submission()->getCollector()
            ->filterByContextIds([$issue->getJournalId()])
            ->filterByIssueIds([$issue->getId()])
            ->filterByStatus($allowedStatuses)
            ->orderBy(\APP\submission\Collector::ORDERBY_SEQUENCE, \APP\submission\Collector::ORDER_DIR_ASC)
            ->getMany();

        $sections = Repo::section()->getByIssueId($issue->getId());
        $issueSubmissionsInSection = [];
        foreach ($sections as $section) {
            $issueSubmissionsInSection[$section->getId()] = [
                'articles' => [],
            ];
        }
        foreach ($submissions as $submission) {
            if (!$sectionId = $submission->getCurrentPublication()->getData('sectionId')) {
                continue;
            }
            $issueSubmissionsInSection[$sectionId]['articles'][] = $submission;
        }

        $submission_ids = [];
        foreach ($sections as $section) {
            $sectionId = $section->getId();
            foreach ($issueSubmissionsInSection[$sectionId]['articles'] as $submission) {
                array_push($submission_ids, $submission->getId());
            }
        }

        return $submission_ids;
    }

    /**
     * Get the currently displayed issue
     *
     * @param Context $context
     * @param string $pageOp
     * @param string $arg
     *
     * @return Issue
     */
    private function getDisplayedIssue(Context $context, string $pageOp, string $arg)
    {
        if ($pageOp == 'index/index' || $pageOp == '/index') {
            $issue = Repo::issue()->getCurrent($context->getId());
        }

        if ($pageOp == 'issue/view') {
            $issue = Repo::issue()->getByBestId($arg, $context->getId());
        }

        if ($pageOp == 'article/view') {
            $submission = Repo::submission()->getByBestId($arg, $context->getId());
            $issue = Repo::issue()->getBySubmissionId($submission->getId());
        }
    
        return $issue;
    }

    /**
     * Get the first issue of the journal
     *
     * @param Context $context
     * @param array $issues
     *
     * @return Issue
     */ 
    private function getFirstIssue(Context $context, array $issues)
    {
        $issue = null;
        $issueId = $issues[count($issues) - 1];
        $issue = Repo::issue()->get($issueId, $context->getId());

        return $issue;
    }

    /**
     * Get the previous issue of the journal given the sequence index of the currently displayed issue
     *
     * @param Context $context
     * @param array $issues
     * @param int $seq
     *
     * @return Issue 
     */
    private function getPreviousIssue(Context $context, array $issues, int $seq)
    {
        $issue = null;
        if ($seq < count($issues) - 1) {
            $issueId = $issues[$seq + 1];
            $issue = Repo::issue()->get($issueId, $context->getId());
        }

        return $issue;
    }

    /**
     * Get the next issue of the journal given the sequence index of the currently displayed issue
     *
     * @param Context $context
     * @param array $issues
     * @param int $seq
     *
     * @return Issue
     */
    private function getNextIssue(Context $context, array $issues, int $seq)
    {
        $issue = null;
        if ($seq > 0) {
            $issueId = $issues[$seq - 1];
            $issue = Repo::issue()->get($issueId, $context->getId());
        }

        return $issue;
    }

    /**
     * Get the currently displayed article
     *
     * @param Context $context
     * @param string $pageOp
     * @param string $arg
     *
     * @return Submission
     */
    private function getDisplayedSubmission(Context $context, string $pageOp, string $arg)
    {
        if ($pageOp == 'article/view') {
            $submission = Repo::submission()->getByBestId($arg, $context->getId());
        }

        return $submission;
    }

    /**
     * Get the first article
     *
     * @param Context $context
     * @param array $submission_ids
     * @param int $seq
     *
     * @return Submission
     */
    private function getFirstArticle(Context $context, array $submission_ids, int $seq)
    {
        $submission = null;
        if ($seq != 0) {
            $submission = Repo::submission()->getByBestId($submission_ids[0], $context->getId());
        }
        return $submission;
    }

    /**
     * Get the previous article given the sequence index of the currently displayed article
     *
     * @param Context $context
     * @param array $submission_ids
     * @param int $seq
     *
     * @return Submission
     */
    private function getPreviousArticle(Context $context, array $submission_ids, int $seq)
    {
        $submission = null;
        if ($seq != 0) {
            $submission = Repo::submission()->getByBestId($submission_ids[$seq-1], $context->getId());
        }
        return $submission;
    }

    /**
     * Get the next article given the sequence index of the currently displayed article
     *
     * @param Context $context
     * @param array $submission_ids
     * @param int $seq
     *
     * @return Submission
     */
    private function getNextArticle(Context $context, array $submission_ids, int $seq)
    {
        $submission = null;
        $last = count($submission_ids) - 1;
        if ($seq < $last) {
            $submission = Repo::submission()->getByBestId($submission_ids[$seq+1], $context->getId());
        }
        return $submission;
    }

    /**
     * Get the last article 
     *
     * @param Context $context
     * @param array $submission_ids
     * @param int $seq
     *
     * @return Submission
     */
    private function getLastArticle(Context $context, array $submission_ids, int $seq)
    {
        $submission = null;
        $last = count($submission_ids) - 1;
        if ($seq < $last) {
            $submission = Repo::submission()->getByBestId($submission_ids[$last], $context->getId());
        }
        return $submission;
    }

    /**
     * Get the sequence from a given submission or issue id
     *
     * @param array $seqToId
     * @param int $id
     *
     * @return int
     */
    private function getSequence(array $seqToId, int $id): int
    {
        $seq = array_search($id, $seqToId);
        return $seq;
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\APP\plugins\blocks\navigation\NavigationBlockPlugin', '\NavigationBlockPlugin');
}
