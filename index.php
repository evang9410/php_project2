<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<form method="get">
    <label> Search </label> <input id="s"type="text" name="search"/>
</form>
<div id="autocomplete"></div>

<script>
    $("#s").keyup(function(e){
        var key = e.keyCode || e.which
        var search = this.value;
        console.log(search);
        $.ajax({
            url:"search.php",
            data:{q:search},
            type:"GET",
            dataType:"json",
            success:function(json){
                //use json object to populate the #autocomplete div
            }
        })

    })
</script>
