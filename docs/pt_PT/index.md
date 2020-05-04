Plug-in para usar o aplicativo Jeedom Móvel.

O aplicativo móvel Jeedom requer a instalação deste plugin para permitir
a caixa pode se comunicar com o aplicativo móvel.

Configuração de plug-in móvel 
==============================

Depois de instalar o plugin, você só precisa ativá-lo :

![mobile1](../images/mobile1.png)

**Configuration**

Para configurar o plug-in, você deve adicionar os telefones que
poderá acessar o Jeedom.

Para adicionar um telefone : **Plugins** → **Communication** → **App
Mobile** → **Ajouter**

![mobile2](../images/mobile2.png)

Aqui estão os parâmetros para inserir :

-   **Nome do equipamento móvel** : Nome do telefone

-   **Activer** : Ativando o acesso para este celular

-   **Tipo de celular** : Seleção de SO do telefone (iOS, Android)

-   **Utilisateur** : Usuário associado a este acesso

> **Tip**
>
> A escolha do usuário é importante porque determina a
> equipamento ao qual este terá acesso de acordo com seus direitos.

![mobile3](../images/mobile3.png)

Após salvar, você receberá um QRCode permitindo
o aplicativo para se configurar.

Configuração de plugins e comandos recebidos pelo aplicativo 
=======================================================

Após inicializar o Móvel Plugin, você tem a opção de
retrabalhar tipos genéricos de controles, plugins e peças.

![mobile10](../images/mobile10.png)

Ao clicar em um plug-in, você pode autorizá-lo ou não a conversar
com o aplicativo móvel e configure cada um dos tipos genéricos
associado a suas ordens.

![mobile11](../images/mobile11.png)

Ao clicar em uma parte, você pode autorizá-la ou não ser
presente no aplicativo móvel e configure cada um dos tipos
genéricos associados a seus pedidos.

![mobile12](../images/mobile12.png)

Configuração de aplicativo para dispositivos móveis 
=====================================

Você encontrará os aplicativos nas persianas móveis :

**Android Google Play**

![Google Play FR](../images/Google_Play_FR.png)

**Apple App Store**

![App Store FR](../images/App_Store_FR.png)

Primeiro lançamento do aplicativo 
--------------------------

No 1º lançamento do aplicativo Mobile, será oferecido um tutorial
para ajudá-lo a configurá-lo.

Depois de baixar e instalar o aplicativo móvel Jeedom,
inicie o aplicativo no seu smartphone.

Você chega em um tutorial de configuração que nós
aconselhar a seguir. Algumas etapas foram realizadas anteriormente.

Você poderá escolher entre uma configuração manual ou
automático por QRcode. Se você escolher a configuração pelo QRcode,
basta piscar o QRcode no plug-in Móvel App em
equipamento para smartphone criado anteriormente. Nesse caso, o aplicativo será
recupera automaticamente toda a configuração do seu Jeedom e
conectar automaticamente. Quando está conectado à sua casa via Wifi,
o aplicativo usará automaticamente o endereço Ethernet da Jeedom
interno à sua rede. Quando você está conectado em 4G ou 3G, há
usará seu endereço externo para conectar-se ao seu Jeedom (por
exemplo via serviço DNS Jeedom, se você o usar). Se você escolher
para configuração manual, nesse caso, você deverá inserir o
entregue os endereços IP internos e externos do seu Jeedom. Esta opção
é reservado a um público informado.

O aplicativo será sincronizado e você chegará à sua página inicial
(precedido por um mini guia de apresentação).

O aplicativo móvel Jeedom está pronto para funcionar.

Favoritos 
-----------

No aplicativo, você pode ter Favoritos (atalhos de
comandos, plugins, cenários).

Aqui está o procedimento para criá-los :

Clique em um dos + na tela inicial do aplicativo :

![mobile dashboard 1](../images/mobile_dashboard_1.PNG)

Você chegará à página de seleção do tipo de atalho :

![mobile dashboard 2](../images/mobile_dashboard_2.PNG)

Por exemplo, vamos tomar uma ação, por isso nos oferece
Quartos / Objetos :

![mobile dashboard 3](../images/mobile_dashboard_3.PNG)

Você só precisa selecionar a ação que deseja
atalho :

![mobile dashboard 4](../images/mobile_dashboard_4.PNG)

É então possível personalizar a cor dele (para o
três cores estão disponíveis) :

![mobile dashboard 5](../images/mobile_dashboard_5.PNG)

Bem como os dois textos associados :

![mobile dashboard 6](../images/mobile_dashboard_6.PNG) ![mobile
painel 7](../ images / mobile_dashboard_7.PNG)

Aqui, você tem agora um atalho do seu pedido (no
versão 1.1 Espera-se que os comandos de ligar / desligar apareçam no
mesma chave).

![mobile dashboard 8](../images/mobile_dashboard_8.PNG)

Como configurar corretamente tipos genéricos 
============================================

Tipos genéricos no plug-in Móvel 
------------------------------------------

Melhor que as palavras, aqui está um exemplo de genéricos típicos para uma
com todos os seus controles (veja também a tabela Light plus
embaixo) :

![generic type in plugin](../images/generic_type_in_plugin.jpg)

Tabelas de modelo de aplicativo 
---------------------------------------

### As luzes #

Imagem                           | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LIGHT](../images/LIGHT_1.jpg) | `Lumière Bouton On`<br/>`Botão apagado` | `LIGHT_ON`<br/>`LIGHT_OFF`| presença de dois botões "ON" e "Off" sem feedback de status. |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton On`<br/>`Botão apagado`<br/>`Luz do estado` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE` | Feedback de status presente, o botão esquerdo alterna entre Ativado e Desativado |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton Toggle`<br/>`Luz do estado` | `LIGHT_TOGGLE`<br/>`LIGHT_STATE` | Feedback de status presente, o botão esquerdo alterna entre Ativado e Desativado |
![LIGHT](../images/LIGHT_3.jpg) | `Lumière Bouton On`<br/>`Botão apagado`<br/>`Luz do estado`<br/>`Light Slider` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER` | Feedback de status presente, o botão esquerdo permite alternar entre On e Off e o controle deslizante permite controlar a intensidade |
![LIGHT](../images/LIGHT_4.jpg) | `Lumière Bouton On`<br/>`Botão apagado`<br/>`Luz do estado`<br/>`Light Slider`<br/>`Cor clara (informação)`<br/>`Cor clara (ação)`<br/>`Light Mode` (opcional, é usado para ter modos de luz, por exemplo, arco-íris na Hue philips) | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER`<br/>`LIGHT_COLOR`<br/>`LIGHT_SET_COLOR`<br/>`LIGHT_MODE` | Feedback de status presente, o botão esquerdo permite alternar entre On e Off e o controle deslizante permite controlar a intensidade. No círculo, a cor da lâmpada está presente e, quando você clica nela, pode alterar a cor e ativar um modo. |

### As tomadas #

Imagem                           | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ENERGY](../images/ENERGY_1.jpg) | `Prise Bouton On`<br/>`Botão fora do soquete`| `ENERGY_ON`<br/>`ENERGY_OFF`| presença de dois botões "ON" e "Off" sem feedback de status. |
![ENERGY](../images/ENERGY_2.jpg) | `Prise Bouton On`<br/>`Botão fora do soquete`<br/>Tomada de Estado | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE` | Feedback de status presente, o botão esquerdo alterna entre Ativado e Desativado |
![ENERGY](../images/ENERGY_3.jpg) | `Prise Bouton On`<br/>`Botão fora do soquete`<br/>Tomada de Estado<br/>`Soquete deslizante` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE`<br/>`ENERGY_SLIDER` | Feedback de status presente, o botão esquerdo permite alternar entre On e Off e o controle deslizante permite controlar a intensidade |

### As persianas #

Imagem                           | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLAP](../images/FLAP_1.jpg)   | `Volet Bouton Monter`<br/>`Painel de botões para baixo`<br/>`Painel do botão Stop '<br/>Painel de estado (opcional) | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE` (opcional) | Presença de três botões "Para cima", "Para baixo", "Parar", feedback opcional do status. |
![FLAP](../images/FLAP_2.jpg)   | `Volet Bouton Monter`<br/>`Painel de botões para baixo`<br/>`Painel do botão Stop '<br/>Painel de estado<br/>`Painel do botão deslizante` | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE`<br/>`FLAP_SLIDER` | Presença de um controle deslizante, com um botão Acima / Abaixo em Alternar (com ícone de status) |

### Inundação #

Imagem                           | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLOOD](../images/FLOOD.jpg)   | `Innondation`<br/>`Temperatura` (opcional)<br/>Humidade (opcional)<br/>Sabotagem (opcional)|`FLOOD`<br/>TEMPERATURA (opcional)<br/>«UMIDADE» (opcional)<br/>«UMIDADE» (opcional) | Permite que você tenha seu sensor de inundação completo em uma única linha.

### Bloquear #

Imagem                         | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LOCK](../images/LOCK.jpg)   | `Bloquear Etat`<br/>`Abrir botão de bloqueio`<br/>`Fechar botão de bloqueio` | `LOCK_STATE`<br/>`LOCK_OPEN`<br/>`LOCK_CLOSE` | Feedback de status presente, o botão esquerdo alterna entre ligado e desligado |

### Sereia #

Imagem                         | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SIREN](../images/SIREN.jpg)   | `Sereia Etat`<br/>`Botão da sirene ligado`<br/>`Botão da sirene desativado` | `SIREN_STATE`<br/>`SIREN_ON`<br/>`SIREN_OFF` | Feedback de status presente, o botão esquerdo alterna entre ligado e desligado |

### Fumaça #

Imagem                           | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SMOKE](../images/SMOKE.jpg)   | `Fumaça`<br/>`Temperatura` (opcional)|`SMOKE`<br/>TEMPERATURA (opcional) | Permite que você tenha seu sensor de fumaça completo em uma única linha.

### Temperatura #

Imagem                                       | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![TEMPERATURE](../images/TEMPERATURE.jpg)   | `Temperatura`<br/>Humidade (opcional)|`TEMPERATURE`<br/>«UMIDADE» (opcional) | Ver imagem.

### Presença #

Imagem                                 | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-----------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![PRESENCE](../images/PRESENCE.jpg)   | `Presença`<br/>`Temperatura` (opcional)<br/>`Brilho` (opcional)<br/>Humidade (opcional)<br/>`UV '(opcional)<br/>Sabotagem (opcional)|`PRESENCE`<br/>TEMPERATURA (opcional)<br/>`BRILHO` (opcional)<br/>«UMIDADE» (opcional)<br/>`UV '(opcional)<br/>`SABOTAGE` (opcional) | Veja a imagem.

### Abertura #

Imagem                                       | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![OPENING](../images/OPENING.jpg)   | `Porte / Fenêtre`<br/>`Temperatura` (opcional)|`OPENING / OPENING_WINDOW`<br/>TEMPERATURA (opcional) | Veja Imagem (ou seja, você pode escolher entre janela e porta).

### Fio piloto #

Imagem                               | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:---------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![HEATING](../images/HEATING.jpg)   | `Chauffage fil pilote Bouton ON`<br/>`Botão OFF do fio piloto de aquecimento`<br/>`Estado do fio piloto de aquecimento`<br/>`Aquecimento do fio piloto de botão` (opcional) | `HEATING_ON`<br/>`HEATING_OFF`<br/>`HEATING_STATE`<br/>`HEATING_OTHER`|Os botões ON / OFF e Status permitem criar o botão na extremidade esquerda do modelo e o `botão de aquecimento do fio piloto` está lá para adicionar botões (no máximo 5)

OS JOKERS 
----------

### Ação genérica #

Imagem                             | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:-------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ACTION](../images/ACTION.jpg)   | `Action Générique`           | `GENERIC_ACTION`             | Le bouton prend la forme du type de l'action. Par défaut c'est un toggle, si c'est un message alors vous avez une enveloppe, si slider vous avez un slider etc...

### Informações genéricas #

Imagem                         | Tipo genérico               | Parte do plugin Dev            | Descrição          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![INFO](../images/INFO.jpg)   | `Information Générique`           | `GENERIC_INFO`             | Le bouton prend la forme du type de l'info.


Solução de problemas 
===============

Ajuda para celular 
-----------

**→ Estou na versão Android do aplicativo (1.0.1 ou 1.0.0) eu não posso
sem acesso aos meus quartos ou mesmo à configuração do aplicativo.**

> **Caution**
>
> Você teve um pop-up avisando sobre uma preocupação com os parâmetros
> acessibilidade, você só precisa ir para o
> configurações de acessibilidade do seu celular e desmarque-as
> aplicativos usando esta opção. (Uma correção será feita
> em breve no aplicativo)

**→ Tenho uma mensagem em uma das linhas dos meus módulos dizendo que
sem um tipo genérico !**

> **Tip**
>
> Ao ler esta mensagem, ele informa qual tipo genérico está ausente para
> crie um modelo compatível. Apenas aplique.
> Consulte o [doc capítulo Tipo
> Générique](https://www.jeedom.com/doc/documentation/plugins/mobile/fr_FR/mobile#_configuration_des_plugins_et_commandes_que_reçoit_l_app).

**→ Tenho um problema com um dos chamados plugins totalmente integrados,
termostato, alarme, câmera) !**

> **Tip**
>
> Não hesite em acessar seu módulo e clicar em
> salvar novamente, isso incluirá novamente os tipos
> créditos associados ao módulo.

**→ Impossível colocar informações sobre as boas-vindas do aplicativo !**

> **Tip**
>
> Isso é normal, estará disponível na versão 1.1.

**→ Tenho o aplicativo que ocupa muita memória no meu
Telefone !**

> **Tip**
>
> Houve um erro nas versões 1.0.0 e 1.0.1 no jogo
> Câmera. O problema não acontecerá novamente com 1.0.2, para excluir
> ocultá-lo sem superestimar o aplicativo, basta ir para a configuração
> do seu aplicativo móvel e clique em "excluir cache".

**→ Tenho uma preocupação com a primeira sincronização no aplicativo ou com o sql no plug-in móvel !**

> **Tip**
>
> você precisa colocar tipos genéricos e autorizar o plug-in a enviar genéricos, veja o documento um pouco mais alto.
