document.getElementById('eventsButton').classList.add('button-pressed');
    function showEvents() {
    document.getElementById('eventsSection').style.display = 'block';
    document.getElementById('friendPostsSection').style.display = 'none';
    document.getElementById('friendsOfFriendsSection').style.display = 'none';
    document.getElementById('partnersSection').style.display = 'none';
    document.getElementById('friendsOfFriendsButton').classList.remove('button-pressed');
    document.getElementById('eventsButton').classList.add('button-pressed');
    document.getElementById('friendPostsButton').classList.remove('button-pressed');
    document.getElementById('partnersButton').classList.remove('button-pressed');

}

function showFriendPosts() {
    document.getElementById('eventsSection').style.display = 'none';
    document.getElementById('friendPostsSection').style.display = 'block';
    document.getElementById('friendsOfFriendsSection').style.display = 'none';
    document.getElementById('partnersSection').style.display = 'none';
    document.getElementById('friendsOfFriendsButton').classList.remove('button-pressed');
    document.getElementById('friendPostsButton').classList.add('button-pressed');
    document.getElementById('eventsButton').classList.remove('button-pressed');
    document.getElementById('partnersButton').classList.remove('button-pressed');

}
function showFriendsOfFriends() {
            document.getElementById('eventsSection').style.display = 'none';
            document.getElementById('friendPostsSection').style.display = 'none';
            document.getElementById('friendsOfFriendsSection').style.display = 'block';
            document.getElementById('partnersSection').style.display = 'none';
            document.getElementById('friendsOfFriendsButton').classList.add('button-pressed');
            document.getElementById('eventsButton').classList.remove('button-pressed');
            document.getElementById('friendPostsButton').classList.remove('button-pressed');
            document.getElementById('partnersButton').classList.remove('button-pressed');

}
 function showPartners() {
            document.getElementById('eventsSection').style.display = 'none';
            document.getElementById('friendPostsSection').style.display = 'none';
            document.getElementById('friendsOfFriendsSection').style.display = 'none';
            document.getElementById('partnersSection').style.display = 'block';

            document.getElementById('eventsButton').classList.remove('button-pressed');
            document.getElementById('friendPostsButton').classList.remove('button-pressed');
            document.getElementById('friendsOfFriendsButton').classList.remove('button-pressed');
            document.getElementById('partnersButton').classList.add('button-pressed');
    }
