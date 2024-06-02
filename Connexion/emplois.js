
window.onload = function() {
    showStage(); // Affiche la section des stages et enfonce le bouton correspondant
};
function showSection(sectionToShow, buttonToActivate) {
    const sections = ['stageSection', 'apprentissageSection', 'cddSection', 'cdiSection'];
    const buttons = document.querySelectorAll('.onglet');

    sections.forEach((section) => {
        document.getElementById(section).style.display = (section === sectionToShow) ? 'block' : 'none';
    });

    buttons.forEach((button) => {
        button.classList.remove('active'); // Réinitialise tous les boutons
    });

    document.querySelector(buttonToActivate).classList.add('active'); // Enfonce le bouton correspondant
}

function showStage() {
    showSection('stageSection', '.onglet:first-child');
}

function showApprentissage() {
    showSection('apprentissageSection', '.onglet:nth-child(2)');
}

function showCDD() {
    showSection('cddSection', '.onglet:nth-child(3)');
}

function showCDI() {
    showSection('cdiSection', '.onglet:nth-child(4)');
}
function showSection(button) {
    const target = button.getAttribute('data-target');
    const sections = ['stageSection', 'apprentissageSection', 'cddSection', 'cdiSection'];
    const buttons = document.querySelectorAll('.onglet');

    sections.forEach((section) => {
        document.getElementById(section).style.display = (section === target) ? 'block' : 'none';
    });

    buttons.forEach((btn) => {
        btn.classList.remove('active');
    });

    button.classList.add('active');
}

function init() {
    const buttons = document.querySelectorAll('.onglet');
    buttons.forEach((button) => {
        button.addEventListener('click', function() {
            showSection(this);
        });
    });

    // Enfonce le bouton "Stage" par défaut
    showSection(buttons[0]);
}

window.onload = init;