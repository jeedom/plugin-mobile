
var tileStates = {};

const saveTileState = (idTile, state) => {
    if (!tileStates[idTile]) {
        tileStates[idTile] = {};
    }
    Object.assign(tileStates[idTile], state);
};


function collectTileState(idTile) {
    const upLeftDiv = document.querySelector(`.tile[id="${idTile}"] .UpLeft`);
    const tileUp = document.querySelector(`.tile[id="${idTile}"] .TileUp`);
    const toggler = document.querySelector('.toggle-switch');
    const iconElement = upLeftDiv.querySelector('i');
    const titleOn = document.querySelector(`.tile[id="${idTile}"] .titleOn`);

    return {
        originalContent: originalContent,
        switchState: toggler ? toggler.classList.contains('active') : null,
        multiState: tileUp ? tileUp.innerHTML : null,
        icon: iconElement ? iconElement.className : null,
        title: titleOn ? titleOn.textContent : null,
        // Ajoutez d'autres propriétés si nécessaire
    };
}



function restoreTileState(tileElement, state) {
    let configTileDiv = document.getElementById('configTileDiv' + tileElement.id);
    for (let key in state) {
        let select = configTileDiv.querySelector(`#${key}`);
        if (select) {
        select.value = state[key];
        }
    }
  }


