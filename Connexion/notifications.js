document.getElementById('eventsButton').classList.add('button-pressed');
function showEvents() {
document.getElementById('eventsSection').style.display = 'block';
document.getElementById('friendPostsSection').style.display = 'none';
document.getElementById('offresEmploisSection').style.display = 'none';
document.getElementById('offresEmploisButton').classList.remove('button-pressed');
document.getElementById('eventsButton').classList.add('button-pressed');
document.getElementById('friendPostsButton').classList.remove('button-pressed');
}

function showFriendPosts() {
document.getElementById('eventsSection').style.display = 'none';
document.getElementById('friendPostsSection').style.display = 'block';
document.getElementById('offresEmploisSection').style.display = 'none';
document.getElementById('offresEmploisButton').classList.remove('button-pressed');
document.getElementById('friendPostsButton').classList.add('button-pressed');
document.getElementById('eventsButton').classList.remove('button-pressed');
}
function showOffresEmplois() {
    document.getElementById('eventsSection').style.display = 'none';
    document.getElementById('friendPostsSection').style.display = 'none';
    document.getElementById('offresEmploisSection').style.display = 'block';
    document.getElementById('offresEmploisButton').classList.add('button-pressed');
    document.getElementById('eventsButton').classList.remove('button-pressed');
    document.getElementById('friendPostsButton').classList.remove('button-pressed');
}