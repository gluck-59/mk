<?php

//error_reporting(0);
if ($_POST['request'])
{
    $request = $_POST['request'];
    $pairs = Ebay_shopping::findPair($request);
    
    echo"<script>jQuery.fn.sortElements=function(){var t=[].sort;return function(e,n){n=n||function(){return this};var r=this.map(function(){var t=n.call(this),e=t.parentNode,r=e.insertBefore(document.createTextNode(''),t.nextSibling);return function(){if(e===this)throw new Error;e.insertBefore(this,r),e.removeChild(r)}});return t.call(this,e).each(function(t){r[t].call(n.call(this))})}}();
    
    setTimeout( function() {   
    $('.sellers').sortElements(function(a, b)
    {
        return parseInt($(a).attr('id')) > parseInt($(b).attr('id')) ? 1 : -1;
    });

    $('.sellers:first').css('background-color', '#cfc');            
    }, 500);
    </script>";    
}

  
// форма
echo '
<form id="pairs" style="width:55%" action="'.$PHP_SELF.'" method="POST" onsubmit="findPairs(); return false">
    <fieldset style="border-radius: 6px;"><legend>Поиск парных лотов</legend>
        <div id="set">';
        
        if ($_POST['request'])
        {
            $fields = explode("+", $_POST['request']);
            foreach ($fields as $key => $value)
            {
                echo '<p><input class="items name="'.$value.'" type="text" placeholder="партномер, название" value="'.$value.'"></p>';
            }

        }
        else echo '<p><input class="items" name="" type="text" placeholder="партномер, название"></p><p><input class="items" type="text" placeholder="партномер, название"></p>';
        
        echo '</div><br><a href="#" onclick="addField()"><img style="" src="../img/admin/add.gif" border="0"> Добавить еще лот
        <br><small>Пустые поля не считаются</small>
        </a><br><br>
        
        <input type="hidden" name="request">
        <br><input type="submit" value="Найти селлеров со всеми лотами"><br><br>';

        if ($pairs)
        {
            echo('<br><hr><br>');
            echo($pairs);
        }

echo '</fieldset>
</form>



<script>
    function addField()
    {
        var x = document.createElement("INPUT");
        x.setAttribute("class", "items");            
        x.setAttribute("type", "text");
        x.setAttribute("placeholder", "партномер, название");
        
        var y = document.createElement("p");
        document.getElementById("set").appendChild(y).appendChild(x);
    }
    
    
    function findPairs()
    {
        var request = "";
        var elems = $(".items:text[value != \"\"]");
        var length = elems.length; 
        elems.each( function(i) {
            request = request + this.value;                    
            if ( i < length-1 )
            {
                request = request + "+"
            }
        });

        $("[name=request]").val(request);
        toastr.success(request, "Запрос findPair:");        
        document.getElementById("pairs").submit();
    }
</script>
';

?>
