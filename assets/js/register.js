$(document).ready(() => {
    //On click signup, hide login and show registration form
    $("#signup").click(() => {
        $("#first").slideUp("slow", () => {
            $("#second").slideDown("slow");
        });
    });

    //On click signin, hide signup and show registration form
    $("#signin").click(() => {
        $("#second").slideUp("slow", () => {
            $("#first").slideDown("slow");
        });
    });
});