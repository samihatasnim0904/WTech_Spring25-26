function analyzeText() {
    let text = document.getElementById("textInput").value;


    if (text.trim() === "") {
        alert("Please enter some text!");
        return;
    }


    let cleanedText = text.trim().replace(/\s+/g, " ");


    let charCount = text.length;


    let words = cleanedText.split(" ");
    let wordCount = words.length;


    let reversedText = text.split("").reverse().join("");


    if (text !== cleanedText) {
        alert("Multiple spaces detected! They were automatically fixed.");
    }


    document.getElementById("result").innerHTML = `
        <p><strong>Total Characters:</strong> ${charCount}</p>
        <p><strong>Total Words:</strong> ${wordCount}</p>
        <p><strong>Reversed Text:</strong><br>${reversedText}</p>
    `;
}