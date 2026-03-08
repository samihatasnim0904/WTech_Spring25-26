document.getElementById("contactForm").addEventListener("submit", function(e){

    e.preventDefault();

    let fname = document.getElementById("fname").value;
    let lname = document.getElementById("lname").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    let message = document.getElementById("message").value;

    if(fname=="" || lname=="" || email=="" || phone=="" || message==""){
        alert("Field Value need to be filled up");
        return;
    }

    console.log("First Name:", fname);
    console.log("Last Name:", lname);
    console.log("Email:", email);
    console.log("Phone:", phone);
    console.log("Message:", message);

    alert("Form Submitted Successfully");

});