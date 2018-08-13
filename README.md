# bootstrap-selector

The most unsual control to find is a checkboxlist or listcheckbox. I bring a bootstrap solution based on [List group](http://getbootstrap.com/components/#list-group-basic).



![alt](https://github.com/pipiscrew/bootstrap-selector/blob/master/screenshot.png)


The element
```
<div id="jobs_users" class="list-group centre" ></div>
```

The functions :
```javascript
$(function() {
    //////////////// SELECTOR
    //**attach the event
    $('#jobs_users').chooser();
    
    
//pass an array with id and usernames to fillList function to autofill the List group
$("#jobs_users").fillList(jArray_USERS, "Users", "id", "user");

//set all items selected
$('#jobs_users').setAll(true);

//get all selected ids
$("#jobs_users").getSelected();

//set selected items by an array contains ids (useful when fetching data from backend)
$("#client_s_users").setSelected(dataP,"user_id")
```



please see the examples.
<br><br>

Example at [jsfiddle](https://jsfiddle.net/s9qezkwz/)
<br><br>


##This project uses the following 3rd-party dependency :<br>
-[Bootstrap](http://getbootstrap.com/)<br>

##This project is no longer maintained
<br><br>
Copyright (c) 2017 [PipisCrew](http://pipiscrew.com)

Licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php).

similar [chosen](https://github.com/harvesthq/chosen)
