      var uri;

      function query(_uri){
        uri = _uri;
        var url = uri+"/api/3/action/group_list";
        $.ajax({
          url:url,
          dataType:"json",
          async:true,
          success: function(json){
            for (group in json.result){
              //var node = document.createElement("LI");
              //var textnode = document.createTextNode(json.result[group]);
              //node.appendChild(textnode);
              //document.getElementById("groups").appendChild(node);
              printGroupInfo(json.result[group]);
            }
          }
        });
      }

      function printGroupInfo(group){
        var url = uri+"/api/3/action/group_show?id="+group;
        var div = document.getElementById('results');
        $.ajax({
          url:url,
          dataType:"json",
          async:true,
          success: function(json){
            //console.log(json);
            var info = "<div class='col-lg-3' id='"+json.result.name+"'>";
            info+="<img src='"+json.result.image_url+"' style='width:25px;height:25px;float:left;'>";
            info += "<h4 style=margin:5px;>"+json.result.display_name+"</h4> <b>User(s)</b>: "+json.result.users.length+"<ul>";
            for (user in json.result.users){
              info+="<li>"+json.result.users[user].name+"</li>";
            }
            info+="</ul><b>Resource(s)</b>: "+json.result.package_count;
            div.innerHTML += info+"</div>";
            getDatasetsFromGroup(json.result.name);
          }
        });
      }

      function getDatasetsFromGroup(group){
        var url = uri+"/api/3/action/package_search?q=groups:"+group+"&rows=3000";
        //console.log(div);
        $.ajax({
          url:url,
          dataType:"json",
          async:true,
          success: function(json){
            console.log(group);
            var div = document.getElementById(group);
            var info = "<ul>";
            for (dataset in json.result.results){
              info+="<li><a href='"+uri+"/dataset/"+json.result.results[dataset].name+"'>"+
              json.result.results[dataset].title+"</a></li>";
              /*for(g in json.result.results[dataset].groups){
                if(json.result.results[dataset].groups[g].name == group){
                  info+="<li><a href='http://giv-oct.uni-muenster.de:5000/dataset/"+json.result.results[dataset].name+"'>"+json.result.results[dataset].title+"</a></li>";
                  console.log(json.result.results[dataset].title);
                }
              }*/

            }
            div.innerHTML += info+"<ul><br>";
            console.log(div);
          }
        });
      }