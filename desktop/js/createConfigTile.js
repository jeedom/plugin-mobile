
const createConfigTile = (tileElement, idTile, randomColor, MODELS_CHOICE) => {
    const configTileDiv = document.createElement('div');
    configTileDiv.classList.add('configTileDiv');
    configTileDiv.setAttribute('id', 'configTileDiv' + idTile);
    configTileDiv.setAttribute('data-id', idTile);
    configTileDiv.style.order = idTile.split('_')[0];

    const bgDiv = createBgDiv(idTile, randomColor);
    configTileDiv.appendChild(bgDiv);

    const firstSection = createFirstSection(MODELS_CHOICE);
    configTileDiv.appendChild(firstSection);

    const cmdSection = createCmdSection(idTile);
    configTileDiv.appendChild(cmdSection);

    const secondSection = createSecondSection(idTile);
    configTileDiv.appendChild(secondSection);

    addHoverAnimations(configTileDiv, tileElement);
    addMouseOutAnimations(configTileDiv, tileElement);
    addTemplateSelectChangeEvent(firstSection.querySelector('select'), tileElement, idTile);

    document.getElementById('rightContent').appendChild(configTileDiv);

    
    if (tileStates[idTile]) {
        restoreTileState(tileElement, tileStates[idTile]);
    }
};

const createBgDiv = (idTile, randomColor) => {
    const bgDiv = document.createElement('div');
    bgDiv.classList.add('bgDiv');
    bgDiv.setAttribute('data-id', idTile);
    bgDiv.setAttribute('style', 'background-color:' + randomColor + ';');
    return bgDiv;
};

const createFirstSection = (MODELS_CHOICE) => {
    const firstSection = document.createElement('div');
    firstSection.classList.add('firstSection');

    const label = createLabel('Type de template à appliquer');
    firstSection.appendChild(label);

    const firstSelect = document.createElement('select');
    firstSelect.setAttribute('id', 'templateSelect');
    firstSelect.classList.add('templateSelect');

    MODELS_CHOICE.forEach(model => {
        const option = document.createElement('option');
        option.value = model.value;
        option.text = model.text;
        firstSelect.appendChild(option);
    });

    firstSection.appendChild(firstSelect);
    return firstSection;
};

const createCmdSection = (idTile) => {
    const cmdSection = document.createElement('div');
    cmdSection.setAttribute('id', 'cmdSection');
    cmdSection.classList.add('cmdSection');

    const labelCmdSection = createLabel('');
    cmdSection.appendChild(labelCmdSection);

    const chooseCmdBtn = createButton(' Choisir Commande', 'btn btn-info btn-sm', 'bt_chooseCmdBtn', 'kiko-several-gears');
    cmdSection.appendChild(chooseCmdBtn);

    chooseCmdBtn.addEventListener('click', () => {
        jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, result => {
            const cmdId = result.cmd.id;
            const humanName = result.human;
            const divSelectedCmd = document.createElement('div');
            divSelectedCmd.classList.add('selectedCmd');
            divSelectedCmd.setAttribute('id', 'selectedCmd' + cmdId);
            divSelectedCmd.setAttribute('data-id', cmdId);
            divSelectedCmd.setAttribute('data-human', humanName);
            divSelectedCmd.innerHTML = humanName;

            if (!document.getElementById('selectedCmd' + cmdId)) {
                cmdSection.appendChild(divSelectedCmd);

                const inputRenameTile = document.createElement('input');
                inputRenameTile.classList.add('inputRenameTile');
                inputRenameTile.setAttribute('type', 'text');
                inputRenameTile.setAttribute('id', 'inputRenameTile' + idTile);
                const tile = document.querySelector(`.tile[id="${idTile}"]`);
                const titleOn = tile.querySelector('.title.on.bold');
                inputRenameTile.value = titleOn.textContent;
                inputRenameTile.setAttribute('data-cmdId', cmdId);
                cmdSection.appendChild(inputRenameTile);

                inputRenameTile.addEventListener('input', event => {
                    const newName = event.target.value;
                    titleOn.textContent = newName;
                    tile.setAttribute('data-title', newName);
                    saveTileState(idTile, collectTileState(idTile));
                });
            } else {
                console.error("divSelectedCmd existe déjà dans le DOM.");
            }
        });
    });

    return cmdSection;
};

const createSecondSection = (idTile) => {
    const secondSection = document.createElement('div');
    secondSection.classList.add('secondSection');

    const chooseIconButton = createButton(' Choisir Icone', 'btn btn-info btn-sm', 'bt_chooseIcon', 'fas fa-flag');
    secondSection.appendChild(chooseIconButton);

    chooseIconButton.addEventListener('click', () => {
        jeedomUtils.chooseIcon(_icon => {
            const iconClassMatch = _icon.match(/class='icon ([^-]+)-([^ ]+)(?: (icon_[^']+))?'/);
            if (iconClassMatch && iconClassMatch[1] && iconClassMatch[2]) {
                const libraryName = iconClassMatch[1];
                const iconClass = iconClassMatch[2];
                const iconColor = iconClassMatch[3] ? iconClassMatch[3].replace('icon_', '') : '';

                const upLeftDiv = document.querySelector(`.tile[id="${idTile}"] .UpLeft`);
                const tile = document.querySelector(`.tile[id="${idTile}"]`);
                tile.setAttribute('data-icon', iconClass);
                tile.setAttribute('data-library', libraryName);
                tile.setAttribute('data-color', iconColor);

                const iconElement = upLeftDiv.querySelector('i');
                iconElement.className = `iconTile ${libraryName}-${iconClass} ${iconClassMatch[3] || ''}`.trim();

                saveTileState(idTile, { icon: iconClass, library: libraryName, color: iconColor });
            }
        });
    });

    return secondSection;
};

const createButton = (text, className, id, iconClass) => {
    const button = document.createElement('a');
    button.classList.add(...className.split(' '));
    button.setAttribute('id', id);

    const icon = document.createElement('i');
    icon.classList.add(...iconClass.split(' '));
    button.appendChild(icon);

    const buttonText = document.createTextNode(text);
    button.appendChild(buttonText);

    return button;
};

const createLabel = (text) => {
    const label = document.createElement('label');
    label.innerHTML = text;
    return label;
};

const addHoverAnimations = (configTileDiv, tileElement) => {
    configTileDiv.addEventListener('mouseover', () => {
        const associatedTile = configTileDiv.getAttribute('data-id');
        if (associatedTile) {
            const associatedElement = document.getElementById(associatedTile);
            associatedElement.style.zIndex = '10';
            associatedElement.classList.add('bounceAndScale');
            associatedElement.addEventListener('animationend', function() {
                this.classList.remove('bounceAndScale');
            });
            const bgDiv = document.querySelector(`.bgDiv[data-id="${associatedTile}"]`);
            bgDiv.style.transform = 'scale(20)';
        }
    });

    tileElement.addEventListener('mouseover', () => {
        const tileId = tileElement.getAttribute('id');
        const bgDiv = document.querySelector(`.bgDiv[data-id="${tileId}"]`);
        if (bgDiv) {
            bgDiv.style.transform = 'scale(20)';
        }
        tileElement.style.transform = 'scale(1.1)';
    });
};

const addMouseOutAnimations = (configTileDiv, tileElement) => {
    configTileDiv.addEventListener('mouseout', () => {
        const associatedTile = configTileDiv.getAttribute('data-id');
        if (associatedTile) {
            const associatedElement = document.getElementById(associatedTile);
            associatedElement.style.transform = 'scale(1.0)';
            associatedElement.style.zIndex = '1';
            const bgDiv = document.querySelector(`.bgDiv[data-id="${associatedTile}"]`);
            bgDiv.style.transform = 'scale(1)';
        }
    });

    tileElement.addEventListener('mouseout', () => {
        const tileId = tileElement.getAttribute('id');
        const bgDiv = document.querySelector(`.bgDiv[data-id="${tileId}"]`);
        if (bgDiv) {
            bgDiv.style.transform = 'scale(1)';
        }
        tileElement.style.transform = 'scale(1.0)';
    });
};

const addTemplateSelectChangeEvent = (firstSelect, tileElement, idTile) => {
    let originalContent = '';

    firstSelect.addEventListener('change', function() {
        let valueChoose = this.value;
        let upLeftDiv = document.querySelector(`.tile[id="${idTile}"] .UpLeft`);
        let upRight = document.querySelector(`.tile[id="${idTile}"] .UpRight`);
        let tileUp = document.querySelector(`.tile[id="${idTile}"] .TileUp`);  
        let containerMultiState = document.querySelector(`.tile[id="${idTile}"] .containerMultiState`); 

        // if (upLeftDiv && originalContent === '') {
        //     originalContent = upLeftDiv.innerHTML;
        // }

        switch (valueChoose) {
            case 'OnOff':
                const switchContainerSpanHtml = createSwitchemplate();
                if (upLeftDiv) {
                    if (containerMultiState) {
                        containerMultiState.style.display = 'none';
                    }
                    upLeftDiv.style.display = 'flex';
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = switchContainerSpanHtml;
                    upLeftDiv.innerHTML = '';
                    upLeftDiv.appendChild(tempDiv.firstElementChild);
                    const toggler = document.querySelector('.toggle-switch');
                    if (toggler) {
                        toggler.onclick = function() {
                            toggler.classList.toggle('active');
                            saveTileState(idTile, { switchState: toggler.classList.contains('active') });
                        };
                    } else {
                        console.error('Toggle switch non trouvé');
                    }
                } else {
                    console.error('UpTitle non trouvé');
                }
                break;
                case 'multistate':
                    const multiStateHtml = createMultiStateTemplate();
                    if (tileUp) {
                        if (upLeftDiv) {
                            upLeftDiv.style.display = 'none';
                        }
                        if (upRight) {
                            upRight.style.display = 'none';
                        }
                        let existingMultiStateContainer = tileUp.querySelector('.containerMultiState');
                        if (!existingMultiStateContainer) {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = multiStateHtml;
                            const newMultiStateContainer = tempDiv.firstElementChild;
                            newMultiStateContainer.style.width = '100%'; 
                            tileUp.appendChild(newMultiStateContainer);
                            saveTileState(idTile, { multiState: true });
                        } else {
                            existingMultiStateContainer.style.display = 'flex';
                            existingMultiStateContainer.style.width = '100%'; 
                        }
                    } else {
                        console.error('TileUp non trouvé');
                    }
                    break;
            default:
                if (upLeftDiv) {
                    upLeftDiv.innerHTML = originalContent;
                    saveTileState(idTile, { template: 'default' });
                }
                break;
        }
    });
};

// function saveTileState(tileElement, idTile) {
//     let configTileDiv = document.getElementById('configTileDiv' + idTile);
//     let selects = configTileDiv.getElementsByTagName('select');
//     let state = {};
//     for (let select of selects) {
//         state[select.id] = select.value;
//     }
//     tileStates[idTile] = state;
// }
// const saveTileState = (idTile, state) => {
//     if (!tileStates[idTile]) {
//         tileStates[idTile] = {};
//     }
//     Object.assign(tileStates[idTile], state);
// };



