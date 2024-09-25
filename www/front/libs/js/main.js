
async function getTweets() {
    let res = await fetch("http://localhost/api/tweets");
    let tweets = await res.json();

    if (!res.ok) {
        let message = "Error:" + Response.status;
        throw new Error(message);
    }
    document.querySelector('.tweets_list').innerHTML='';
    tweets.forEach((tweet) => {
        let created_at = tweet.CreatedAt.split(' ')[0];

            document.querySelector('.tweets_list').innerHTML += `
            <div class="card mb-3">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Имя: ${tweet.Username}</h6>
                <p class="card-text">${tweet.Content}</p>
                <div class="d-flex justify-content-between">
                  <p class="card-text">Категория: ${tweet.Category_title}</p>
                  <p class="card-text ">${created_at}</p> 
                </div>
                
                <button class="remove btn btn-danger" data-id="${tweet.Id}" >Удалить</button>
              </div>
            </div>`
          ;
          
    });
    
}

async function getCategories(){
    let res = await fetch("http://localhost/api/categories");
    let cats = await res.json();

    if (!res.ok) {
        let message = "Error:" + Response.status;
        throw new Error(message);
    }
    const categorySelect = document.getElementById("Category-id");
    cats.forEach((cat)=>{
        const option = document.createElement('option');
        option.value = cat.Id;
        option.text = cat.Title;
        if (cat.Id ===1 ){
            
            option.setAttribute('selected', true);
        }
        
        categorySelect.add(option);
    });

}

async function addTweet(e){
    e.preventDefault();
    
    let username = document.getElementById("Username").value;
    let content = document.getElementById("Content").value;

    const categorySelect = document.getElementById("Category-id");
    let category_id = categorySelect .value;


    let js = {
        "Username":username,
        "Content":content,
        "Category_id":category_id,

    };
     let data2 = JSON.stringify(js);
     
    let res = await fetch("http://localhost/api/tweets",{
        method:"POST",
        body:JSON.stringify(js)
    });
   

    let data = await res.json();
    console.log("DATAAAAA",data);
    if (data.status===true){
        await getTweets();
    }

    username.value = "";
    content.value = "";
    
}


document.addEventListener('DOMContentLoaded', function(){
     getTweets();
     getCategories();
});

 setInterval(getTweets, 2000);


async function removeTweet(id) {
    let res= await fetch(`http://localhost/api/tweets/${id}`, {
        method:"DELETE",
    });

    const data = await res.json();

    if (data.status===true){
        await getTweets();
    }
}

document.getElementById('tweetForm').addEventListener('submit', addTweet);


document.querySelector('.tweets_list').addEventListener('click', function(event) {
    
    console.log(event);
    if (event.target.classList.contains('remove')) {
        let tweet_id = event.target.getAttribute('data-id');
        console.log("REMOVEEEEEE", tweet_id);
        removeTweet(tweet_id);
        
    }
});


