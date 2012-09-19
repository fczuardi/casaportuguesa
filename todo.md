Lançamento
====================

frontend
------
- OK - [home-velha] contagem regressiva, ao chegar no zero deve mudar o texto para:
  "a qualquer momento as primeiras fotos aparecerão aqui, fique ligado"
- OK - [bug] mouseover esta variando conforme tamanho da janela

- [home-velha] quando contador estiver no zero a pagina da refresh a cada 30 segundos.
- [home] exibir numero de likes no hover sas fotos de destaque
- [admin] clique no textfield das recentes deve selecionar tudo automaticamente
- [admin] onchance dos textfields dos destaques ja deve dar um preview da foto que o cara colou

backend
------
- admin blacklist para fotos
- admin blacklist para usuarios
- terminar o cron que passa por todas as fotos e atualiza os likes
- trigger para mudança da home na primeira foto do usuario:antoniograssi
  - id = 3229331
  - primeiro destaque ja vai ser preenchido com a foto deste user
- script para limpar fotos que não são mais acessíveis


Locaweb
-------
- por senha no arquivo do phpliteadmin
- bug do heart.png (abrir pedido)






Next
=====

frontend
---------

backend
-------
- real time ajax polling para as recentes enquanto a pagina está aberta
- pensar no fim da promoção
  - testar 5k requests no menor tempo possivel
  - parar o cron pelo menos 1 hora antes da paulada final


Limbo
=====
- fazer layout de pop-up bonitinho com a frase atual + "De 19 de Setembro de 2012 até 30 de Janeiro de 2013."
- logo da Funarte (futuro próximo)







































Segunda 16-setembro:
====================

Pessoal
-------
- OK - Pagar condominio e escola
- OK - cancelar aula de guitarra de terça

Locaweb
-------

- OK - por senha no arquivo de admin

backend
------
- OK - data de criacao no banco
- OK - adicionar campo featured no bd
- OK - trigger para atualizar bd com novas fotos a partir da callback do real-time
- OK - converter home para php e carregar listas de fotos recentes do banco
- OK - listar as features na home
  - OK - se tem feature vazia, preenche com top-like


frontend
------
- OK - lazy loading
- OK - implementar mouseover nas recentes pequenas


- versao admin da home
  - OK - textfield de ID em todas as fotos pequenas
  - OK - textfield sobre as de destaque e um botao de atualizar (copia o id de baixo e cola em cima)
  - OK - dois checkboxes em cada foto:
    - OK - blacklist user
    - OK - blacklist photo























































OK - IE9, 8, 7, 6
=================

- WONTFIX - [ie9] [ie8] [ie7] [ie6] [windows] Não pega Helvetica / Helvetica Light

- OK - [ie8] [bug] Erro de Javascript no Date.now()
- OK - [ie9] balao de coletivos não respeita o alinhamento de foto e texto
- OK - [ie8] Ícone do instagram está pulando de linha
  - OK - [ie8] Baloes sem foto estao dando uma margem no primeiro paragrafo
- OK - [ie8] Vermelho do balão não tem opacidade
- OK - [ie8] Icones do twitter e facebook aparecem bem fracos
- OK - [ie8] Bordas não arredondadas nos balões
- OK - [ie7] menu superior está cagado
- OK - [ie7] tudo se mexe quando vc da um hover em um link (tabs e twitter)
- OK - [ie7] todos os digitos estão com o hr zoado
- OK - [ie7] z-index do balão não é respeitado (aperece debaixo da sombra)
- OK - [ie6] Ajustes IE6 + chrome-frame

* * *

- OK - R:NAO - aquele icone do instagram é clicável, se sim, linka para onde/o que?
- OK - hover numa aba mostra o risco rosa, clique abre o balão rosa com texto
- OK - remover o pontilhadinho default do browser do link ativo nas tabs
- OK - numeros com opacidade 80%
- OK - cada balão pode ter largura e posicionamento diferente
- OK - relogio funcionando em javascript
- OK - incluir o link de inauguração do projeto (http://www.facebook.com/umacasaportuguesacomcerteza)
- OK - incluir link para download do PDF
- OK - leia/baixe o regulamento: 0. Baixe o regulamento - Clique aqui e baixe o regulamento para guardar, imprimir ou ler com calma
- OK - testar em IE9 e IE8
- OK - IE6 para baixo exibir chromeframe
- OK - trocar o texto da tela inicial por popup verde
- OK - popup verde quando possivel deve aparecer vindo de cima para baixo a partir do header
- OK - ao clicar no x de fechar a popup verde deve subir, e voltar na versao pequena
- OK - ao clicar na versao pequena da popup verde a popup pequena deve subir e voltar como popup grande
- OK - 2. popup aprovado + incluir um abrir fechar, sendo q quando fechado fica a mostra a frase: Qual a sua imagem da casa portuguesa?
- OK - 3. alterar o txt do pop para Qual a sua imagem da casa portuguesa?
- OK - 4. link da inauguração do projeto vai p face abrindo uma aba nova
- OK - 1. ballon fecha quando clica no link ou q lugar fora
- OK - fazer um html de mentira com o png v10 centralizado


- OK - desligar o live.js!!!!
- WONTFIX - adaptacao pro php prover quantos milisegundos faltam
- OK - para onde devem os links de facebook e twitter linkar?
- WONTFIX - botar o username do cara do lado da foto, como em http://blog.instagram.com/post/24408162773/whp-watchingwatchers
- OK - fazer balao fechar tambem quando vc clica fora dele

- OK - criar HTML com PNG de como seria a promo funcional para o Iatã aprovar
- OK - colocar a frase antes do contador



























