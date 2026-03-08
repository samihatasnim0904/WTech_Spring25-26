// // console.log("HTML page are connected")
// // // alert("HTML Connected")
// // // let a=20.56;
// // // let b=40;
// // // let c=a+b;

// // // console.log("Sum of c is: ", c)
// // // if(a==0){
// // //     a++;
// // //     console.log("Increment value",a)
// // // }
// // // else if(a>20 && a<25){
// // //     b=a+b;
// // //     console.log("Current value of b", b)
// // // }

// // // for(let i=0; i<5; i++){
// // //     console.log(i)
// // // }
// // // var name="AIUB";
// // // var name;
// // // var name="BUET";
// // // var name;
// // // console.log(name);

// // function collect_data(){
// // let name = document.getElementById("Name").value;
// // console.log(name);

// // let PAge = document.getElementById("Age").value;
// // console.log(PAge);

// // let DateOfBirth =document.getElementById("DOB".value);
// // console.log(DateOfBirth); 

// // return false;
// // }

// // console.log("Connect");
// //     var a = ["ABC","DEF"];
// //     a.forEach((item.index)=>{
// //         console.log("Index: ", index, "Item"= item);
// //     })


// let clickcount

// // a.map((item.index)=>{
// //         console.log("Index: ", index, "Item"= item);
// //     })

// function get_textarea()
// {
    
//     let patientAdd = document.getElementById("Address").value;
//     console.log(patientAdd)
//     return false;

// }

// function submit_key{
//     clickcount++;
//     let submitdata=document.getElementById("keysubmit").value;
//     document.getElementById("keysubmit").style.color="green";
//     document.getElementById("keysubmit").innerHTML="Again Submit" +clickcount;

//     return false;
// }

console.log("Connect HTML Page");
var a = ["ABC", "DEF"];

let clickcount=0;

a.forEach((item, index)=>{
    console.log("Index: ",index, "Item:", item);
})
a.map((item, index)=>{
    console.log("Index: ",index, "Item:", item);
})

function collect_data()
{
    let name = document.getElementById("PatientName").value;
    console.log(name);
    document.getElementById("PatientName").style.color="red";

    return false;
}

function get_textarea()
{
    let patientAdd= document.getElementById("Address").value;
    console.log(patientAdd);
    return false;
}

function submit_key()
{
    clickcount++;
    let submitdata = document.getElementById("keysubmit").value;
    document.getElementById("keysubmit").style.color="green";
    document.getElementById("keysubmit").innerHTML="Again Submit" + clickcount;
    return false;
}