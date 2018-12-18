$(document).ready(function(){
    $("a.toggle-confirm").click(function(){
        return confirm("Are you sure?"); // Will return false and stop default behaviour if clicked "Cancel"
    });
});