<?php
require_once 'class.citiesDAO.php';
$cdao = new CitiesDAO();
session_start();
session_regenerate_id();
$login_id = $_SESSION['login_id'];
$user_search_history= $cdao->get_user_history($login_id);
if(isset($_POST["search"])){
    if(!empty($_POST["search"])){
        $city = strip_tags($_POST["search"]);
        $cdao->insert_user_history($login_id, $city, time());
    }
}
if(isset($_POST['logout'])){
    session_destroy();
    header('Location: ./index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./styles/style.css">
</head>
<body id="search_index">
    <div class="" id='header'><?php echo "Welcome $login_id!"; ?></div>
    <div class="container-fluid" id='wrapper'>
        <div class="row">
            <div class="col-md-2">
                <h4>Search History</h4>
                <div id="history">
                    <ul id = "history_list" class="list-group"></ul>
                </div>
            </div>
            <div class = "col-md-4" id="search_group">
                <div class="input-group">
                    <form method = "POST" action="" class="input-group">
                        <input id="s"type="text" class="form-control" name="search" list="autocomplete" autocomplete="off" placeholder="Search Cities..."/>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary" name="submit" onclick="onSubmit">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </span>
                        <datalist id="autocomplete"></datalist>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="col-md-2 foot">
            <form action="" method="POST">
                <button type="submit" class="btn btn-danger" name="logout">Logout</button>
            </form>
       </div>
</body>
</html>

<script>
    $("#s").keyup(function(e){
        var key = e.keyCode || e.which
        if(key == 40 | key == 38){ // allows you to down and up arrow through the datalist without calling the ajax request.
            return;
        }
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
                    //option.innerHTML = city.name;
                    //div.innerHTML += city.name +", " + city.country + "<br/>";
                    datalist.appendChild(option);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });

    $(document).ready(function(){
        // load users search history if it exists.
        $.ajax({
            url:"history.php",
            dataType:"json",
            success:function(json){
                var ul = document.getElementById("history_list");
                for(var i = 0; i < json.length; i++){
                    console.log(json[i].city_name);
                    var li = document.createElement("li");
                    li.className += " list-group-item list-group-item-info";
                    li.innerHTML = json[i].city_name;
                    ul.appendChild(li);
                }
            }
        })
    });
    function onSubmit(){
        var searchBox = document.getElementById("s");
        if(s.value == undefined | s.value == ""){
                s.value = document.getElementByID("autocomplete").firstChild.innerHTML;
        }
    }
</script>
