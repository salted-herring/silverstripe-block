$curLink
<div id="block-$ID" class="block group"<% if $frontendEditable %> style="position: relative;"<% end_if %>>
	<% if not $hideTitle %>
	<h2 class="block-title"><span>$Title</span></h2>
	<% end_if %>
	$Layout
	<% if $frontendEditable %>
	<div style="position: absolute; top: 0; right: 0;">
		<a href="/admin/blocks/Block/EditForm/field/Block/item/{$ID}/edit" target="_blank">Edit</a>
	</div>
	<% end_if %>
</div>