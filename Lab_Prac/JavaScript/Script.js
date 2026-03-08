function collect_name()
{
    let name = document.getElementById("fname").value;

    if(name == "")
    {
        document.getElementById("NameError").innerHTML = "Name cannot be empty";
        return false;
    }
    else if(name.length < 5)
    {
        document.getElementById("NameError").innerHTML = "Name must carry at least 5 characters";
        return false;
    }
    else
    {
        document.getElementById("NameError").innerHTML = "";
        return true;
    }
}


document.getElementById("contactForm").addEventListener("submit", function(e){

    e.preventDefault();

    let fname = document.getElementById("fname").value;
    let lname = document.getElementById("lname").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    let message = document.getElementById("message").value;

    if(fname=="" || lname=="" || email=="" || phone=="" || message=="")
    {
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



function collet_data()
{
    let isnamevalid= collect_name();
    let isageValid = collect_age();
// let name = document.getElementById("PatientName").value;
// console.log(name);


// let PatientAge = document.getElementById("Age").value;
// console.log(PatientAge);

// let DateOfBirth = document.getElementById("DOB").value;
// console.log(DateOfBirth); 

// let name = document.getElementById("PatientName");
// var para1 = document.getElementById("demo");
// para1.innerHTML="Patient Name: "+ name.value;

// let age = document.getElementById("Age");
// var para2 = document.getElementById("PAge");
// para2.innerHTML= "Patient Age: " + age.value;


return false;
}