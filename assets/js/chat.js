let timer = setInterval(getXML, 1000);

function getXML() {
    let httpRequest = new XMLHttpRequest();

    httpRequest.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            displayChat(this.responseXML);
        }
    };

    httpRequest.open("POST", "chatMessagesXML/"+sessionStorage.getItem("email")+".php");
    httpRequest.send();
}

function displayChat(xml) {
    let messages = xml.getElementsByTagName("message");
	//chatResults is a div container in an HTML file.
	//it is use to put all the messages.
    let chatResult = document.getElementById("chat-results");
    chatResult.innerHTML = "";

    for(let counter=0; counter<messages.length; counter++){
        let messageTag = document.createElement("p");
        let messageText = "";
        
		//This if is for your messages. The else is for other users messages.
		//Their format and class is different.
        if(messages[counter].getAttribute("sender") == sessionStorage.getItem("email")){
            messageTag.classList.add("your-message");
            messageText = messages[counter].innerHTML;
        } else {
            messageTag.classList.add("other-user-message");
            messageText = messages[counter].getAttribute("sender")+ " : " +messages[counter].innerHTML;
        }

        messageTag.appendChild(document.createTextNode(messageText));
        
        chatResult.appendChild(messageTag);
        chatResult.appendChild(document.createElement("br"));
    }
    updateScroll(chatResult);
}

function insertXML(message) {
    let httpRequest = new XMLHttpRequest();

    httpRequest.onreadystatechange = function(){
        if(this.readyState == 4){
            if(this.status == 200){
                console.log(this.responseText);
                document.getElementById("chatbox").value = "";
                hasScrolled = false;
            } else {
                console.log("Connection failed");
            }
        }
    };

    httpRequest.open("POST", "modify-xml.php");
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send("message="+encodeURIComponent(message));
}

//The comments below are the reason why I did what I did.

let hasScrolled = false;
//hasScrolled is true when the page is not at the bottom due to user scrolling.
//when hasScrolled is false, then the page is at the bottom.
//And therefore when there is new messages I want to autoScroll the page
//to go to the bottom again.

//the need for the variable below is when the function updateScroll() 
//trigger the event onscroll below and that means user didn't scroll up. 
let hasScrolledByUpdateScroll = false;


document.getElementById("chat-results").onscroll = function(e){
	//The if below happens when user scrolled up therefore hasScrolled must be true.
    if(!hasScrolledByUpdateScroll){
        hasScrolled = true;
    } else {
        hasScrolledByUpdateScroll = false;
    }
	
	//The reason behind the code below is when user scroll up
	//and scroll back at the bottom of the page.
	//And therefore hasScrolled must be false.
    if(this.scrollTop >= this.scrollHeight - this.offsetHeight){
        hasScrolled = false;
    }
}

//When there is new messages it has to go to bottom of the page to see it.
//and updateScroll is the responsible for it.
function updateScroll(element){
    if(hasScrolled == false){
       element.scrollTop = element.scrollHeight;
       hasScrolledByUpdateScroll = true;
    }
}
