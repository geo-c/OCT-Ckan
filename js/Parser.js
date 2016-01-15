		var ckanInstance = new Array();
		var notCkanInstance= new Array();
		var json;
			
		function initParser(){
				
			//uses a local copy of http://dataportals.org/api/data.json. Has to be updated regularly.
				$.getJSON( "data/data.json", function( data ) {
					json = data;
					/*
					$("#buttons").append("<button onclick=separateCKAN()>CKAN</button>");
					$("#buttons").append("<button onclick=countMetadata()>Metadata</button>");
					$("#buttons").append("<button onclick=countPortalsWithLocation()>Portals with location</button>");
					$("#buttons").append("<button onclick=checkLicense()>License</button>");
					$("#buttons").append("<button onclick=checkLanguage()>Language</button>");
					*/
					
					$("#buttons").append('<label class="btn btn-default"><input type="radio" name="options" onchange=separateCKAN()>CKAN</input></label>');
					$("#buttons").append('<label class="btn btn-default"><input type="radio" name="options" onchange=countMetadata()>Metadata</input></label>');
					$("#buttons").append('<label class="btn btn-default"><input type="radio" name="options" onchange=countPortalsWithLocation()>Portals with location</input></label>');
					$("#buttons").append('<label class="btn btn-default"><input type="radio" name="options" onchange=checkLicense()>License</input></label>');
					$("#buttons").append('<label class="btn btn-default"><input type="radio" name="options" onchange=checkLanguage()>Language</input></label>');

					//The following tries to access information of each CKAN instance, here the number of datasets included in each data portal.
					//Does not work for all instances due to a different structure of the particular endpoint.
					ckanInstance.forEach(function(val, i){
						$.ajax({
							url: val.apiendpoint + "search/dataset",
							type: "POST",   
							dataType: 'json',
							cache: false,
							success: function(response){
								//$("#urls").append("<p>" + val.apiendpoint + ": " + response.count+ "</p>");
								console.log(val.apiendpoint)
								console.log(response.count);
							}           
						});
					});
				});
			}
			
			function separateCKAN(){
				//the following separates CKAN instances from non CKAN instances 
				jQuery.each(json, function(i, val) {
					if (val.generator.includes("CKAN")){
						val.apiendpoint = checkURL(val.apiendpoint);
						//$("#urls").append("<p>" + val.title + ": " + val.apiendpoint+ "</p>");
						ckanInstance.push(val);
					} else {
						var ckan = false;
						for (var i = 0; i < val.tags.length; i++){
							if (val.tags[i] === "ckan"){
								val.url = checkURL(val.url);
								val.apiendpoint = val.url + "api/";
								//$("#urls").append("<p>" + val.title + ": " + val.apiendpoint+ "</p>");
								ckan = true;
								ckanInstance.push(val);
							} 
						}
						if (!ckan){
							notCkanInstance.push(val);
						}
					}
				});
				$("#urls").empty();
				$("#urls").append("<p> CKAN instances: " + ckanInstance.length + ". No CKAN instance: " + notCkanInstance.length+ "</p>");				
			}
			
			//counts how many of the dataportals have metadata.
			function countMetadata(){
				var metadata = 0;
				var nometadata = 0;
				jQuery.each(json, function(i, val) {
					if (val.metadatacreated === ""){
						nometadata++;
					}else{
						metadata++
					}
				});
				$("#urls").empty();
				$("#urls").append("<p> Metadata available: " + metadata + ". No metadata available: " + nometadata+ "</p>");
			}
			
			//counts, how many of the dataportals have a location, i. e. coordinates.
			function countPortalsWithLocation(){
				var withLocation = 0;
				var withoutLocation = 0;
				jQuery.each(json, function(i, val) {
					if (val.location === ""){
						withoutLocation++;
					}else{
						withLocation++;
					}
				});			
				$("#urls").empty();
				$("#urls").append("<p> Location available: " + withLocation + ". No location available: " + withoutLocation+ "</p>");				
			}
			
			//counts how many of the dataportals have no language information, which languages occur and how often.
			function checkLanguage(){
				var noLanguageInfo = 0;
				var languages = new Array();
				jQuery.each(json, function(i, val) {
					if (val.language === ""){
						noLanguageInfo++;
					}else{
						var language = val.language.split(" ");
						for ( var i = 0; i < language.length; i++){
							var included =  checkIfIncluded(languages, language[i].toLowerCase()); 
							if (included === false){
								languages.push({type:language[i].toLowerCase(), frequency: 1});
							}else{
								languages[included].frequency = languages[included].frequency + 1;
							}
						}
					}
				});
				$("#urls").empty();
				for (var i = 0; i < languages.length; i++){
					$("#urls").append("<p>" + languages[i].type + ": " + languages[i].frequency + " time(s).</p>");
				}
				$("#urls").append("<p>No language information: " + noLanguageInfo + "</p>");
			}
			
			//counts how many do not have a license, which licenses occur and how often.
			function checkLicense(){
				var licenseType = new Array();
				var noLicenseInfo = 0;
				var noLicenseSpecified = 0;
				var licenseUnknown = 0;
				jQuery.each(json, function(i, val) {
					var licenseID = val.licenseid;
					var licenseURL = val.licenseurl; 
					if (licenseID === ""  && licenseURL === ""){
						noLicenseInfo++;
					}else if (licenseID === "notspecified" && licenseURL === ""){
						noLicenseSpecified++;
					}else if (licenseID === "Unknown" && licenseURL === ""){
						licenseUnknown++;
					}else{
						var included =  checkIfIncluded(licenseType, licenseID);
						if (included === false){
							licenseType.push({type:licenseID, frequency: 1});
						}else{
							licenseType[included].frequency = licenseType[included].frequency + 1;
						}
					}
				});
				$("#urls").empty();
				for (var i = 0; i < licenseType.length; i++){
					$("#urls").append("<p>" + licenseType[i].type + ": " + licenseType[i].frequency + " time(s).</p>");
				}
				$("#urls").append("<p>No license information: " + noLicenseInfo + "</p>");
				$("#urls").append("<p>No license specified: " + noLicenseSpecified + "</p>");
				$("#urls").append("<p>License unknown: " + licenseUnknown + "</p>");		
			}
			
			//checks if something is already included in an array.
			function checkIfIncluded(arr, type){
				included = false;
				for (var i = 0; i < arr.length; i++){
					if ( arr[i].type === type ){				
						included = i;
					}
				}
				return included;
			}
			
			//creates endpoints that fit for most of the instances.
			function checkURL(url){
				var checkedURL = url
				if (checkedURL.substring(checkedURL.length-1, checkedURL.length) === "/"){
					//do nothing
				}else{
					checkedURL = checkedURL + "/";
				}
				return checkedURL
			}