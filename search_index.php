<?php
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
session_start();
session_regenerate_id();
$login_id = $_SESSION['login_id'];
$user_search_history= $cdao->get_user_history($login_id);
if(isset($_POST['logout'])){
    session_destroy();
    header('Location: ./index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./styles/style.css">
</head>
<body>
    <div id='container'><?php echo "Welcome $login_id!"; ?></div>
    <div id='wrapper'>

            <label> Search </label> <input id="s"type="text" name="search" list="autocomplete"/>
            <datalist id="autocomplete"></datalist>
            <form action="" method="POST">
                <button type="submit" class="btn btn-default" name="logout">Logout</button>
            </form>
            </body>
            </html>
    </div>

<script>
    $("#s").keyup(function(e){
        var key = e.keyCode || e.which
        console.log(key);
        var search = this.value;
        var datalist = document.getElementById('autocomplete');
        if(search == ""){ // if the textbox is empty, don't query database.
            $("#autocomplete").empty();
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
                    var option = document.createElement('option');
                    option.value = "No results";
                    datalist.appendChild(option);
                    return; // I can easily just do if(json.cities.length != 0)
                            // and encapulate the rest in there. But...no. Sorry.
                }
                $("#autocomplete").empty();
                for(var i = 0; i < json.cities.length; i++){
                    var city = json.cities[i];
                    //console.log(city.name);
                    var option = document.createElement('option');
                    option.value = city.name + ", " + city.country;
                    //div.innerHTML += city.name +", " + city.country + "<br/>";
                    datalist.appendChild(option);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });


        if(key == 13){
            var item = $("#s").val(datalist.firstChild.value);
            $.ajax({
                url:"history.php",
                data:{item: item},
                type:"POST",
                dataType:"json",
                success:function(json){
                    
                }
            })
        }

    })
</script>
