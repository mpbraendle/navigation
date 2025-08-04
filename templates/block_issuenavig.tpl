{**
 * templates/block.tpl
 *
 * Copyright (c) 2014-2024 Simon Fraser University
 * Copyright (c) 2003-2024 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @brief Common sidebar menu for navigating issues.
 *
 * @uses $contextUrl - the URL of the journal
 * @uses $displayedIssue - currently displayed issue
 * @uses $firstIsse - first issue
 * @uses $previousIssue - previous issue
 * @uses $nextIssue - next issue
 * @uses $lastIssue - last (usually the current) issue
 *}
<div class="pkp_block block_navigation_issues">
	<h2 class="title">
		{translate key="plugins.block.navigation.issues"}
	</h2>

	<nav class="content" role="navigation" aria-label="{translate|escape key="plugins.block.navigation.issues"}">
		<ul class="nav_issues">
            {if $firstIssue}
                <li class="nav_first_issue">
                    <a class="nav_issue_icon" href="{$contextUrl}/issue/view/{$firstIssue->getBestIssueId()}" alt="{translate key="plugins.block.navigation.firstissue"}" title="{translate key="plugins.block.navigation.firstissue"}"></a>
                    <a class="nav_issue_desc" href="{$contextUrl}/issue/view/{$firstIssue->getBestIssueId()}">
                        {translate key="plugins.block.navigation.firstissue"}
                    </a>
                    <div class="nav_issue_bibl">
                        {capture assign="vl"}{$firstIssue->getVolume()}{/capture}
                        {capture assign="no"}{$firstIssue->getNumber()}{/capture}
                        {capture assign="yr"}{$firstIssue->getYear()}{/capture}
                        {if $vl}
                            {translate key="plugins.block.navigation.volume"} {$vl}
                        {/if}
                        {if $no}
                            {translate key="plugins.block.navigation.number"} {$no}
                        {/if}
                        {if $yr}
                            ({$yr})
                        {/if}
                    </div>
                    <div class="nav_issue_title">
                        {capture assign="ti"}{$firstIssue->getLocalizedTitle()}{/capture}
                        {if $ti}
                            {$ti}
                        {/if}
                    </div>
                </li>
            {/if}
			{if $previousIssue}
				<li class="nav_previous_issue">
					<a class="nav_issue_icon" href="{$contextUrl}/issue/view/{$previousIssue->getBestIssueId()}" alt="{translate key="plugins.block.navigation.previousissue"}" title="{translate key="plugins.block.navigation.previousissue"}"></a>
					<a class="nav_issue_desc" href="{$contextUrl}/issue/view/{$previousIssue->getBestIssueId()}">
						{translate key="plugins.block.navigation.previousissue"}
					</a>
					<div class="nav_issue_bibl">
						{capture assign="vl"}{$previousIssue->getVolume()}{/capture}
						{capture assign="no"}{$previousIssue->getNumber()}{/capture}
						{capture assign="yr"}{$previousIssue->getYear()}{/capture}
						{if $vl}
							{translate key="plugins.block.navigation.volume"} {$vl}
						{/if}
						{if $no}
							{translate key="plugins.block.navigation.number"} {$no}
						{/if}
						{if $yr}
							({$yr})
						{/if}
					</div>
					<div class="nav_issue_title">
						{capture assign="ti"}{$previousIssue->getLocalizedTitle()}{/capture}
						{if $ti}
							{$ti}
						{/if}
					</div>
				</li>
			{/if}
			<li class="nav_displayed_issue">
				<div class="nav_issue_bibl">
					{capture assign="vl"}{$displayedIssue->getVolume()}{/capture}
					{capture assign="no"}{$displayedIssue->getNumber()}{/capture}
					{capture assign="yr"}{$displayedIssue->getYear()}{/capture}
					{if $vl}
						{translate key="plugins.block.navigation.volume"} {$vl}
					{/if}
					{if $no}
						{translate key="plugins.block.navigation.number"} {$no}
					{/if}
					{if $yr}
						({$yr})
					{/if}
				</div>
				<div class="nav_issue_title">
					{capture assign="ti"}{$displayedIssue->getLocalizedTitle()}{/capture}
					{if $ti}
						{$ti}
					{/if}
				</div>
			</li>
			{if $nextIssue}
				<li class="nav_next_issue">
					<a class="nav_issue_icon" href="{$contextUrl}/issue/view/{$nextIssue->getBestIssueId()}" alt="{translate key="plugins.block.navigation.nextissue"}" title="{translate key="plugins.block.navigation.nextissue"}"></a>
					<a class="nav_issue_desc" href="{$contextUrl}/issue/view/{$nextIssue->getBestIssueId()}">
						{translate key="plugins.block.navigation.nextissue"}
					</a>
					<div class="nav_issue_bibl">
						{capture assign="vl"}{$nextIssue->getVolume()}{/capture}
						{capture assign="no"}{$nextIssue->getNumber()}{/capture}
						{capture assign="yr"}{$nextIssue->getYear()}{/capture}
						{if $vl}
							{translate key="plugins.block.navigation.volume"} {$vl}
						{/if}
						{if $no}
							{translate key="plugins.block.navigation.number"} {$no}
						{/if}
						{if $yr}
							({$yr})
						{/if}
					</div>
					<div class="nav_issue_title">
						{capture assign="ti"}{$nextIssue->getLocalizedTitle()}{/capture}
						{if $ti}
							{$ti}
						{/if}
					</div>
				</li>
			{/if}
			{if $lastIssue}
				<li class="nav_last_issue">
					<a class="nav_issue_icon" href="{$contextUrl}/issue/view/{$lastIssue->getBestIssueId()}" alt="{translate key="plugins.block.navigation.lastissue"}" title="{translate key="plugins.block.navigation.lastissue"}"></a>
					<a class="nav_issue_desc" href="{$contextUrl}/issue/view/{$lastIssue->getBestIssueId()}">
						{translate key="plugins.block.navigation.lastissue"}
					</a>
					<div class="nav_issue_bibl">
						{capture assign="vl"}{$lastIssue->getVolume()}{/capture}
						{capture assign="no"}{$lastIssue->getNumber()}{/capture}
						{capture assign="yr"}{$lastIssue->getYear()}{/capture}
						{if $vl}
							{translate key="plugins.block.navigation.volume"} {$vl}
						{/if}
						{if $no}
							{translate key="plugins.block.navigation.number"} {$no}
						{/if}
						{if $yr}
							({$yr})
						{/if}
					</div>
					<div class="nav_issue_title">
						{capture assign="ti"}{$lastIssue->getLocalizedTitle()}{/capture}
						{if $ti}
							{$ti}
						{/if}
					</div>
				</li>
			{/if}
			<li class="nav_all_issues">
				<a class="nav_all_issues_icon" href="{$contextUrl}/issue/archive" alt="{translate key="plugin.block.navigation.archives"}" title="{translate key="plugin.block.navigation.archives"}"></a>
				<a class="nav_all_issues_desc" href="{$contextUrl}/issue/archive">{translate key="plugin.block.navigation.archives"}</a>
			</li>
		</ul>
	</nav>
</div>
