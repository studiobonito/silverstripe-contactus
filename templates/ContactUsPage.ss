<section role="main" id="primary-content">
	
	<h1>$Title</h1>
			
	<% if ErrorMessage %>
	<div class="message error">
		<p>$ErrorMessage</p>
	</div>
	<% end_if %>
			
	<% if SuccessMessage %>
	<div class="message success">
		<p>$SuccessMessage</p>
	</div>
	<% end_if %>
	
	<div class="content">	
		<div class="article-content">
			<h2>Request a call back</h2>
			
			$Content

			$ContactForm
				
		</div>		

		<aside role="complementary" class="vcard">
		
			<h2 class="tel">Tel: $ContactTelephone</h2>
			<p>email: <a class="email" href="mailto:{$ContactEmail}?subject=Enquiry%20or%20Comments">{$ContactEmail}</a></p>
			
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
			
			<div id="map">
				$ContactMap
			</div>
		</aside>

	</div><!-- ./content -->

</section>