let search = document.getElementById('searchBox');
let dropDMenu = document.getElementById('drop-d-menu');
let loadingIcon = document.getElementById('loading');
let recordDisplay = document.getElementById('record-display');



// alert(search);

search.addEventListener('keyup', searchText);

function searchText(){

    let searchText = search.value;
    // alert(searchText);


    loadingIcon.style.visibility = 'visible';
    loadingIcon.style.opacity = 1;

    if(search.value == ""){
        loadingIcon.style.visibility = 'hidden';
        loadingIcon.style.opacity = 0;
        dropDMenu.style.visibility = 'hidden';
        dropDMenu.style.opacity = 0;
    }else{


        if(isLoggedIn){
            xml.open('GET', "./search.php?searchTxt="+searchText+"&userId="+loggedInUser+ "&token="+token);
            xml.send();
        }

        if(isLoggedIn ==false){
            xml.open('GET', "./search.php?searchTxt="+searchText+"&token="+token);
            xml.send();
        }

        /* An event handler. It is called when the readyState property of the XMLHttpRequest object
        changes. */
        xml.onreadystatechange = function () {
            /* Checking if the request is successful or not. */
            if (this.readyState == 4 && this.status == 200) {

                /* Declaring a variable named `a` and assigning it to an empty string. */
                var a = '';
                /* Creating an anchor tag. */
                a = document.createElement('a');

                /* Assigning a class to the anchor tag. */
                a.className = ' hover-shadow text-decoration-none   mt-2 w-auto d-flex justify-content-center text-light';

                /* Clearing the content of the element with the id of `record-display`. */
                recordDisplay.innerHTML = '';

                /* Parsing the JSON response from the server. */
                var users = JSON.parse(this.responseText);

                loadingIcon.style.visibility = 'hidden';
                loadingIcon.style.opacity = 0;
                dropDMenu.style.visibility = 'visible';
                dropDMenu.style.opacity = 1;

                /* Creating a table with the data from the people array. */
                users.forEach(function (obj) {
                    

                    /* Checking if the user is logged in or not. If the user is not logged in, it will
                    add the class of `dropDMenu` to the drop down menu. It will also set the
                    visibility and opacity of the drop down menu to hidden and 0 respectively. */
                    if(isLoggedIn == false){

                        /* Creating a table with the name and email of the user. */
                        a.innerHTML = '<img src="./images/' + obj.photo_name + '" alt="Photo" id="imgTag" class=" rounded-3 img-thumbnail" width="100" height="100"/>' +
                            '<div class="d-md-flex w-100 h-100">' +
                                '<table class="table align-self-center text-bark"> ' +
                                    '<thead class="fs-6" > <tr><th class="border-0 " scope="col">Name</th>' +
                                        '<th class="border-0 " scope="col">Email</th></tr>' +
                                    '</thead>' +
                                    '<tbody ><tr><td style="word-break:break-all;" class="border-0 text-wrap ">' + obj.first_name +' '+obj.last_name+ '</td>' +
                                        '<td style="word-break:break-all;" class="border-0 " id="txtFlow">' + obj.email + '</td></tr>' +
                                    '</tbody>' +
                                '</table>' +
                            '</div>';
                        /* Appending the anchor tag to the element with the id of `record-display`. */
                        recordDisplay.append(a);
                    }else{
                        /* Removing the class of `dropDMenu` from the element with the id of
                        `drop-d-menu`. */
                        dropDMenu.classList.remove('dropDMenu');

                        /* Creating a table with the user's name, username, email, and a button to send
                        a friend request. */
                        a.innerHTML = '<img src="./images/' + obj.photo_name + '" alt="Photo" id="imgTag" class=" rounded-3 img-thumbnail" width="100" height="100"/>' +
                            '<div class="d-md-flex w-100 h-100">' +
                                '<table class="table align-self-center text-dark"> ' +
                                    '<thead class="fs-6" > <tr><th class="border-0 " scope="col">Name</th>' +
                                        '<th class="border-0 " scope="col">Action</th>' +
                                        '<th class="border-0 d-none d-sm-table-cell" scope="col">Username</th>' +
                                        '<th class="border-0 d-none d-lg-table-cell" scope="col">Email</th></tr>' +
                                    '</thead>' +
                                    '<tbody ><tr><td style="word-break:break-all;" class="border-0 text-wrap ">' + obj.first_name +' '+obj.last_name+ '</td>' +
                                        '<td style="word-break:break-all;" class="border-0 text-wrap "> ' +
                                        '<button class="btn btn-sucess bg-success text-light" id="sendReq">Add</button> </td>' +
                                        '<td style="word-break:break-all;" class="border-0 d-none d-sm-table-cell" id="txtFlow">' + obj.username + '</td>' +
                                        '<td style="word-break:break-all;" class="border-0 d-none d-lg-table-cell" id="txtFlow">' + obj.email + '</td></tr>' +
                                    '</tbody>' +
                                '</table>' +
                            '</div>';
                        /* Appending the anchor tag to the element with the id of `record-display`. */
                        recordDisplay.append(a);

                    }

                });

                /* Checking if the length of the people array is less than or equal to 0. If it is, it
                adds the class dropDMenu to the dropDMenu variable. It then adds the text "No Record
                Found!" to the a variable. It then appends the a variable to the recordDisplay
                variable. */
                if(users.length <=0){
                    dropDMenu.classList.add('dropDMenu');
                    a.innerHTML = '<h3 class="text-dark">No Record Found!</h3>';
                    recordDisplay.append(a);

                }

            }
        };

    }

}