<% if not $UseOwnTemplate %>
<<% if $SectionWrapper %>section<% else %>div<% end_if %> id="block-$ID" class="block block-{$Type2Class} group<% if $WrapperClasses %> $WrapperClasses<% end_if %><% if $addMarginTop %> margin-top<% end_if %><% if $addMarginBottom %> margin-bottom<% end_if %><% if $addPaddingTop %> padding-top<% end_if %><% if $addPaddingBottom %> padding-bottom<% end_if %><% if $frontendEditable %> edit-mode<% end_if %>">
	
	<% if not $SectionWrapper %>
		<% if not $hideTitle %>
			<% if $TitleWrapper %>
				<{$TitleWrapper} class="block-title<% if $HeadingClasses %> $HeadingClasses<% end_if %>"><span>$Title</span></{$TitleWrapper}>
			<% else %>
				<h2 class="block-title<% if $HeadingClasses %> $HeadingClasses<% end_if %>"><span>$Title</span></h2>
			<% end_if %>
		<% end_if %>
	<% else %>
		<header class="block-section-header<% if $hideTitle %> hidden<% end_if %>">
			<% if $TitleWrapper %>
				<{$TitleWrapper} class="block-title<% if $HeadingClasses %> $HeadingClasses<% end_if %>"><span>$Title</span></{$TitleWrapper}>
			<% else %>
				<h2 class="block-title<% if $HeadingClasses %> $HeadingClasses<% end_if %>"><span>$Title</span></h2>
			<% end_if %>
		</header>
	<% end_if %>
	
	$Layout
	<% if $frontendEditable %>
	<div class="frontend-block-edit-wrapper" style="position: absolute; top: 0; right: 0;">
		<a class="block-edit-button" href="/admin/blocks/Block/EditForm/field/Block/item/{$ID}/edit" target="_blank">Edit</a>
	</div>
	<% end_if %>
</<% if $SectionWrapper %>section<% else %>div<% end_if %>>
<% else %>
	$Layout
<% end_if %>