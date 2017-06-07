{{--<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">--}}
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Simples-Minimalistic Responsive Template</title>
		<!--suppress CssUnusedSymbol -->
		<style type="text/css">
			/* Client-specific Styles */
			#outlook a { padding : 0; }
			
			/* Force Outlook to provide a "view in browser" menu link. */
			body { width : 100% !important; -webkit-text-size-adjust : 100%; -ms-text-size-adjust : 100%; margin : 0; padding : 0; }
			
			/* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
			.ExternalClass { width : 100%; }
			
			/* Force Hotmail to display emails at full width */
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height : 100%; }
			
			/* Force Hotmail to display normal line spacing.*/
			#backgroundTable { margin : 0; padding : 0; width : 100% !important; line-height : 100% !important; }
			
			img { outline : none; text-decoration : none; border : none; -ms-interpolation-mode : bicubic; }
			
			a img { border : none; }
			
			.image_fix { display : block; }
			
			p { margin : 0 0 !important; }
			
			table td { border-collapse : collapse; }
			
			table { border-collapse : collapse; mso-table-lspace : 0; mso-table-rspace : 0; }
			
			a { color : #0a8cce; text-decoration : none !important; }
			
			/*STYLES*/
			table[class=full] { width : 100%; clear : both; }
			
			/*IPAD STYLES*/
			@media only screen and (max-width : 640px) {
				a[href^="tel"], a[href^="sms"] {
					text-decoration : none;
					color           : #0a8cce; /* or whatever your want */
					pointer-events  : none;
					cursor          : default;
				}
				
				.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
					/*text-decoration: default;*/
					color          : #0a8cce !important;
					pointer-events : auto;
					cursor         : default;
				}
				
				table[class=devicewidth] { width : 440px !important; text-align : center !important; }
				
				table[class=devicewidthinner] { width : 420px !important; text-align : center !important; }
				
				img[class=banner] { width : 440px !important; height : 220px !important; }
				
				img[class=colimg2] { width : 440px !important; height : 220px !important; }
				
			}
			
			/*IPHONE STYLES*/
			@media only screen and (max-width : 480px) {
				a[href^="tel"], a[href^="sms"] {
					text-decoration : none;
					color           : #0a8cce; /* or whatever your want */
					pointer-events  : none;
					cursor          : default;
				}
				
				.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
					/*text-decoration: default;*/
					color          : #0a8cce !important;
					pointer-events : auto;
					cursor         : default;
				}
				
				table[class=devicewidth] { width : 280px !important; text-align : center !important; }
				
				table[class=devicewidthinner] { width : 260px !important; text-align : center !important; }
				
				img[class=banner] { width : 280px !important; height : 140px !important; }
				
				img[class=colimg2] { width : 280px !important; height : 140px !important; }
				
			}
		</style>
	</head>
	<body>
		<table width="100%" bgcolor="#ededed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable"
			st-sortable="preheader">
			<tbody>
				<tr>
					<td>
						<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
							hasbackground="true">
							<tbody>
								<tr>
									<td width="100%">
										<table width="600" cellpadding="0" cellspacing="0" border="0" align="center"
											class="devicewidth">
											<tbody>
												<!-- Spacing -->
												<tr>
													<td width="100%" height="10"></td>
												</tr>
												<!-- Spacing -->
												<tr>
													<td>
														<table width="100" align="left" border="0" cellpadding="0"
															cellspacing="0">
															<tbody>
																<tr>
																	<td align="left" valign="middle"
																		style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #666666"
																		st-content="viewonline">
																		<p></p>
																	</td>
																</tr>
															</tbody>
														</table>
														<table width="100" align="right" border="0" cellpadding="0"
															cellspacing="0" class="devicewidth">
															<tbody>
																<tr>
																	<td width="30" height="30" align="right">
																		<div class="imgpop">
																			<a href="https://www.facebook.com/boozrunapp">
																				<img st-image="facebook"
																					src="{{asset("branding/facebook_logo.png")}}"
																					alt="" border="0" width="30"
																					height="30"
																					style="display:block; border:none; outline:none; text-decoration:none;"
																					id="clkf0yproaz11pq9cu9639pb9">
																			</a>
																		</div>
																	</td>
																	<td align="left" width="20"
																		style="font-size:1px; line-height:1px;"></td>
																	<td width="30" height="30" align="center">
																		<div class="imgpop">
																			<a href="https://www.twitter.com/boozrunapp">
																				<img
																					src="{{asset("branding/twitter_logo.png")}}"
																					st-image="twitter" alt="" border="0"
																					width="30" height="30"
																					style="display:block; border:none; outline:none; text-decoration:none;"
																					id="tjntx0la5vf7zvfj2l7lul3di">
																			</a>
																		</div>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<!-- Spacing -->
												<tr>
													<td width="100%" height="10"></td>
												</tr>
												<!-- Spacing -->
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table width="100%" bgcolor="#ededed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable"
			st-sortable="banner">
			<tbody>
				<tr>
					<td>
						<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
							hasbackground="true">
							<tbody>
								<tr>
									<td width="100%">
										<table width="600" align="center" cellspacing="0" cellpadding="0" border="0"
											class="devicewidth">
											<tbody>
												<tr>
													<!-- start of image -->
													<td align="center">
														<div class="imgpop">
															<a href="https://www.boozrunapp.com">
																<img width="600" border="0" height="300"
																	st-image="banner" alt=""
																	style="display:block; border:none; outline:none; text-decoration:none;"
																	src="@yield("headerImage")" class="banner"
																	id="headerImage">
															</a>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
										<!-- end of image -->
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table width="100%" bgcolor="#ededed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable"
			st-sortable="full-text">
			<tbody>
				<tr>
					<td>
						<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
							bgcolor="#ffffff" hasbackground="true">
							<tbody>
								<tr>
									<td width="100%">
										<table width="600" cellpadding="0" cellspacing="0" border="0" align="center"
											class="devicewidth" bgcolor="#ffffff">
											<tbody>
												<!-- Spacing -->
												<tr>
													<td height="20"
														style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;"></td>
												</tr>
												<!-- Spacing -->
												<tr>
													<td>
														<table width="560" align="center" cellpadding="0"
															cellspacing="0" border="0" class="devicewidthinner">
															<tbody>
																<!-- Title -->
																<tr>
																	<td style="font-family: Helvetica, arial, sans-serif; font-size: 30px; color: #333333; text-align:center; line-height: 30px;"
																		st-title="fulltext-heading">
																		<p align="center"></p>
																	</td>
																</tr>
																<!-- End of Title --><!-- spacing -->
																<tr>
																	<td width="100%" height="20"
																		style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;"></td>
																</tr>
																<!-- End of spacing --><!-- content -->
																<tr>
																	<td style="font-family: Helvetica, arial, sans-serif; font-size: 16px; color: #666666; text-align:center; line-height: 30px;"
																		st-content="fulltext-content">
																		<p style="text-align: left;">
																			@yield("emailText")
																		</p>
																	</td>
																</tr>
																<!-- End of content -->
															</tbody>
														</table>
													</td>
												</tr>
												<!-- Spacing -->
												<tr>
													<td height="20"
														style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;"></td>
												</tr>
												<!-- Spacing -->
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table width="100%" bgcolor="#ededed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable"
			st-sortable="separator">
			<tbody>
				<tr>
					<td>
						<table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth"
							hasbackground="true">
							<tbody>
								<tr>
									<td align="center" height="30" style="font-size:1px; line-height:1px;"></td>
								</tr>
								<tr>
									<td width="550" align="center" height="1" bgcolor="#d1d1d1"
										style="font-size:1px; line-height:1px;"></td>
								</tr>
								<tr>
									<td align="center" height="30" style="font-size:1px; line-height:1px;"></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table width="100%" bgcolor="#ededed" cellpadding="0" cellspacing="0" border="0" id="backgroundTable"
			st-sortable="footer">
			<tbody>
				<tr>
					<td>
						<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"
							hasbackground="true">
							<tbody>
								<tr>
									<td width="100%">
										<table width="600" cellpadding="0" cellspacing="0" border="0" align="center"
											class="devicewidth">
											<tbody>
												<tr>
													<td align="center" valign="middle"
														style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #666666"
														st-content="postfooter">
														<p style="text-align: center;">
															Have Questions? Contact us at
															<a href="mailto:info@boozrunapp.com">
																info@boozrunapp.com
															</a>
														</p>
														{{--<p>--}}
														{{--Click here to--}}
														{{--<a style="text-decoration: none; color: #0a8cce" href="#">--}}
														{{--Unsubscribe--}}
														{{--</a>--}}
														
														{{--</p>--}}
														<p></p>
														<p style="text-align: left;"></p>
													</td>
												</tr>
												<!-- Spacing -->
												<tr>
													<td width="100%" height="20"></td>
												</tr>
												<tr>
													<td align="center" valign="middle"
														style="font-family: Helvetica, arial, sans-serif; font-size: 14px;color: #666666"
														st-content="postfooter">
														<p style="text-align: left;">
															<a target="_blank"
																href="https://itunes.apple.com/us/app/boozrun-alcohol-delivery/id1013230156?mt=8&ign-mpt=uo%3D4#">
																<img
																	style="display:inline; border:none; outline:none; text-decoration:none;"
																	src="{{asset("branding/app_store_button.png")}}">
															</a>
															<img
																style="display:inline; border:none; outline:none; text-decoration:none;"
																src="{{asset("branding/play_store_button.png")}}">
														</p>
													</td>
												</tr>
												<!-- Spacing -->
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>