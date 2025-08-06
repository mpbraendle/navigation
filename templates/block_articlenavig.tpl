{**
 * templates/block_articlenavig.tpl
 *
 * Copyright (c) 2014-2025 Simon Fraser University
 * Copyright (c) 2003-2025 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @brief Common sidebar menu for navigating articles.
 *
 * @uses $contextUrl - the URL of the journal
 * @uses $previousIssueArticle - previous issue, first article
 * @uses $nextIssueArticle - next issue, first article
 * @uses $firstArticle - first article 
 * @uses $previousArticle - previous article
 * @uses $nextArticle - next article
 * @uses $lastArticle - last article
 * @uses $tocPath - link to Table of Contents
 *}
<div class="pkp_block block_navigation_articles">
    <h2 class="title">
        {translate key="plugins.block.navigation.articles"}
    </h2>

    <nav class="content" role="navigation" aria-label="{translate|escape key="plugins.block.navigation.articles"}">
        {* Issue navigation *}
        <ul class="nav_issues">
            {if $previousIssueArticle}
                <li class="nav_previous_issue">
                    <a class="nav_issue_icon" href="{$contextUrl}/article/view/{$previousIssueArticle->getBestId()}" alt="{translate key="plugins.block.navigation.previousissuearticle"}" title="{translate key="plugins.block.navigation.previousissuearticle"}"></a>
                    <a class="nav_issue_desc" href="{$contextUrl}/article/view/{$previousIssueArticle->getBestId()}">
                        {translate key="plugins.block.navigation.previousissuearticle"}
                    </a>
                </li>
            {/if}
            {if $nextIssueArticle}
                <li class="nav_next_issue">
                    <a class="nav_issue_icon" href="{$contextUrl}/article/view/{$nextIssueArticle->getBestId()}" alt="{translate key="plugins.block.navigation.nextissuearticle"}" title="{translate key="plugins.block.navigation.nextissuearticle"}"></a>
                    <a class="nav_issue_desc" href="{$contextUrl}/article/view/{$nextIssueArticle->getBestId()}">
                        {translate key="plugins.block.navigation.nextissuearticle"}
                    </a>
                </li>
            {/if}
        </ul>

        {* Article navigation *}
        <ul class="nav_articles">
            {if $firstArticle}
                <li class="nav_first_article">
                    <a class="nav_article_icon" href="{$contextUrl}/article/view/{$firstArticle->getBestId()}" alt="{translate key="plugins.block.navigation.firstarticle"}" title="{translate key="plugins.block.navigation.firstarticle"}"></a>
                    <a class="nav_article_desc" href="{$contextUrl}/article/view/{$firstArticle->getBestId()}">
                        {translate key="plugins.block.navigation.firstarticle"}
                    </a>
                    <div class="nav_article_title">
                        {assign var="ti" value=$firstArticle->getCurrentPublication()->getLocalizedTitle(null, 'html')|strip_unsafe_html}
                        {if $ti}
                            {$ti}
                        {/if}
                    </div>
                </li>
            {/if}
            {if $previousArticle}
                <li class="nav_previous_article">
                    <a class="nav_article_icon" href="{$contextUrl}/article/view/{$previousArticle->getBestId()}" alt="{translate key="plugins.block.navigation.previousarticle"}" title="{translate key="plugins.block.navigation.previousarticle"}"></a>
                    <a class="nav_article_desc" href="{$contextUrl}/article/view/{$previousArticle->getBestId()}">
                        {translate key="plugins.block.navigation.previousarticle"}
                    </a>
                    <div class="nav_article_title">
                        {assign var="ti" value=$previousArticle->getCurrentPublication()->getLocalizedTitle(null, 'html')|strip_unsafe_html}
                        {if $ti}
                            {$ti}
                        {/if}
                    </div>
                </li>
            {/if}
            {if $nextArticle}
                <li class="nav_next_article">
                    <a class="nav_article_icon" href="{$contextUrl}/article/view/{$nextArticle->getBestId()}" alt="{translate key="plugins.block.navigation.nextarticle"}" title="{translate key="plugins.block.navigation.nextarticle"}"></a>
                    <a class="nav_article_desc" href="{$contextUrl}/article/view/{$nextArticle->getBestId()}">
                        {translate key="plugins.block.navigation.nextarticle"}
                    </a>
                    <div class="nav_article_title">
                        {assign var="ti" value=$nextArticle->getCurrentPublication()->getLocalizedTitle(null, 'html')|strip_unsafe_html}
                        {if $ti}
                            {$ti}
                        {/if}
                    </div>
                </li>
            {/if}
            {if $lastArticle}
                <li class="nav_last_article">
                    <a class="nav_article_icon" href="{$contextUrl}/article/view/{$lastArticle->getBestId()}" alt="{translate key="plugins.block.navigation.lastarticle"}" title="{translate key="plugins.block.navigation.lastarticle"}"></a>
                    <a class="nav_article_desc" href="{$contextUrl}/article/view/{$lastArticle->getBestId()}">
                        {translate key="plugins.block.navigation.lastarticle"}
                    </a>
                    <div class="nav_article_title">
                        {assign var="ti" value=$lastArticle->getCurrentPublication()->getLocalizedTitle(null, 'html')|strip_unsafe_html}
                        {if $ti}
                            {$ti}
                        {/if}
                    </div>
                </li>
            {/if}
            <li class="toc">
                <a class="nav_toc_icon" href="{$tocPath}" alt="{translate key="plugins.block.navigation.toc"}" title="{translate key="plugins.block.navigation.toc"}"></a>
                <a class="nav_toc_desc" href="{$tocPath}">
                    {translate key="plugins.block.navigation.toc"}
                </a>
            </li>
        </ul>
    </nav>
</div>
