<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<form method="get" action="">
    <label> Search </label> <input id="s"type="text" name="search"/>
</form>
<div id="autocomplete"></div>

<script>
    $("#s").keyup(function(e){
        var key = e.keyCode || e.which
        var search = this.value;
        var div = document.getElementById('autocomplete');
        if(search == ""){ // if the textbox is empty, don't query database.
            div.innerHTML = "";
            return; // leave the function in an preposterous way, sorry.
        }

        $.ajax({
            url:"search.php",
            data:{q:search},
            type:"GET",
            dataType:"json",
            success:function(json){
                //use json object to populate the #autocomplete div
                if(json.cities.length == 0){ // if the search returns no results.
                    div.innerHTML = "No results.";
                    return; // I can easily just do if(json.cities.lenght != 0)
                            // and encapulate the rest in there. But...no. Sorry.
                }
                div.innerHTML = "";
                for(var i = 0; i < json.cities.length; i++){
                    var city = json.cities[i];
                    //console.log(city.name);
                    div.innerHTML += city.name +", " + city.country + "<br/>";
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });

    })
</script>
</body>
</html>
