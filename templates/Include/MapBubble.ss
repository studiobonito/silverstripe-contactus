<section class="address">
	<h2>$LocationTitle</h2>
	<address class="adr">
		<% if LocationAddress1 %><span class="street-address">$LocationAddress1</span><% end_if %>
		<% if LocationAddress1 %><span class="extended-address">$LocationAddress2</span><% end_if %>
		<% if LocationAddress1 %><span class="locality">$LocationTownCity</span><% end_if %>
		<% if LocationAddress1 %><span class="region">$LocationCounty</span><% end_if %>
		<% if LocationAddress1 %><span class="country-name">$LocationCountry</span><% end_if %>
		<% if LocationAddress1 %><span class="postal-code">$LocationPostcode</span><% end_if %>
	</address>
</section>