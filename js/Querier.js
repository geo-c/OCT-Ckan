
      /*
      * Start the querying process by getting the group list from the CKAN API
      * .
      * @param {String} uri - The URI of the ckan server
      * @param {String} divId - Id of the div where the results will be displayed
      */
      function query(uri, divId){
        var url = uri+"/api/3/action/group_list";
        $.ajax({
          url:url,
          dataType:"json",
          async:true,
          success: function(json){
            for (group in json.result){
              printGroupInfo(json.result[group], divId);
            }
          }
        });
      }

      /*
      * Query and display the information of a given CKAN group
      *
      * @param {String} group - CKAN id of the group
      */
      function printGroupInfo(group, divId){
        var url = uri+"/api/3/action/group_show?id="+group;
        // The information will be displayed in the results div
        var div = document.getElementById(divId);
        $.ajax({
          url:url,
          dataType:"json",
          async:true,
          success: function(json){
            // The group data will be displayed in a div named CKAN+GroupID
            var info = "<div class='col-lg-3' id='CKAN"+json.result.name+"'>";
            info+="<img src='"+json.result.image_url+"' style='width:25px;height:25px;float:left;'>";
            info += "&nbsp;&nbsp;<h4 style=margin:5px;>"+json.result.display_name+"</h4> <b>User(s)</b>: "+json.result.users.length+"<ul>";
            for (user in json.result.users){
              info+="<li>"+json.result.users[user].name+"</li>";
            }
            info+="</ul><b>Resource(s)</b>: "+json.result.package_count;
            div.innerHTML += info+"</div>";
            getDatasetsFromGroup(json.result.name);
          }
        });
      }

      /*
      * Query and display the datasets of a given group
      *
      * @param {String} group - CKAN id of the group
      */
      function getDatasetsFromGroup(group){
        var url = uri+"/api/3/action/package_search?q=groups:"+group+"&rows=3000";
        $.ajax({
          url:url,
          dataType:"json",
          async:true,
          success: function(json){
            console.log(group);
            // The information is displayed in the div (CKAN+GroupID) previously created
            var div = document.getElementById("CKAN"+group);
            var info = "<ul>";
            for (dataset in json.result.results){
              info+="<li><a href='"+uri+"/dataset/"+json.result.results[dataset].name+"'>"+
              json.result.results[dataset].title+"</a></li>";
            }
            div.innerHTML += info+"<ul><br>";
            console.log(div);
          }
        });
      }
