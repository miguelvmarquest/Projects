"""
Trabalho de Sistemas Inteligentes
Realizado por :
	Miguel Marques, nº47139
	Duarte Sousa, nº47164

"""

from collections import namedtuple
from jogo_avanco_37 import *
from jogadores_avanco_37 import *

"""
ESTAS FORAM A FUNÇÕES QUE USAMOS PARA TESTE 
--------------------------------------------

O resultado de um jogo é um tuplo com três componentes:
- O tipo de resultado. Tipicamente: 'vitoria' ou 'empate', mas
poderão existir variantes, dependendo do jogo.
- A cor associada ao resultado. Tipicamente: 'brancas' ou 'pretas'. Poderá 
também ser outro valor, por exemplo,'nenhum', nos casos em que há empate.
- O nome do jogador vencedor, ou 'nenhum', no caso de empate.
"""
ResultadoJogo = namedtuple('ResultadoJogo','tipo, cor, nome')
                           
def um_jogo(game, jogador1, jogador2, nivel, verbose = False) :
    """
    """
    estado = game.initial
    if verbose :
        game.display(estado)
    
    fim = False
    jogs = [jogador1,jogador2]
    ind_proximo = 0
    
    while not fim :
        jogada = jogs[ind_proximo].funcao(game,estado,nivel)
        estado = game.result(estado,jogada)
        if verbose :
            game.display(estado)

        fim = game.terminal_test(estado)
        ind_proximo = 1 - ind_proximo
    
    utilidade = game.utility(estado, game.to_move(game.initial))
    if utilidade == 1 :
        resultado = ResultadoJogo('vitoria',game.jogadores[0],jogador1.nome)
    elif utilidade == -1 :
        resultado = ResultadoJogo('vitoria',game.jogadores[1],jogador2.nome)
    else :
        resultado = ResultadoJogo('empate','nenhum','nenhum')
    
    return resultado


def um_par_de_jogos(game,j1,j2,nivel,verbose = False) :
    """
    Realização de um par de jogos entre dois jogadores, de modo a que cada
    jogador faça um jogo em cada lado ('brancas' ou pretas')
    É devolvido um par de resultados.
    """
    r1 = um_jogo(game,j1,j2,nivel,verbose)
    r2 = um_jogo(game,j2,j1,nivel,verbose)
    return (r1,r2)

def n_pares_de_jogos(game,n,j1,j2,nivel,verbose = False) :
    """Realização de n pares de jogos entre os jogadores j1 e j2, 
    alternando os papéis (brancas ou pretas) de cada jogador.
    É devolvido um 5-tuplo com:
    - número de vitórias de j1 jogando com as brancas
    - número de vitórias de j1 jogando com as pretas
    - número de vitórias de j2 jogando com as brancas
    - número de vitórias de j2 jogando com as pretas
    - númeor de empates
    """
    vit1B, vit1P, vit2B, vit2P, emp = (0,0,0,0,0)
    for _ in range(n) :
        (r1,r2) = um_par_de_jogos(game,j1,j2,nivel,verbose)
        if r1.tipo == "empate" :
            emp += 1
        elif r1.cor == game.jogadores[0] :
            vit1B += 1
        else :
            vit2P += 1
        if r2.tipo == "empate" :
            emp += 1
        elif r2.cor == game.jogadores[0] :
            vit2B += 1
        else :
            vit1P += 1
    
    return vit1B, vit1P, vit2B, vit2P, emp, j1, j2



##########################################################
##               COMO CORRER O JOGO?                    ##
#--------------------------------------------------------#
# APENAS TEM DE TIRAR DE COMENTARIO AS LINHAS SEGUINTES  #
##########################################################


jogo = JogoAvanco()

## FAZ UM JOGO ENTRE O JOGADOR 1 E O JOGADOR 2 UTILIZANDO A FUNCAO
## DE AVALIACAO F4 E A FUNCAO DE JOGADA ALEATORIA
######################----CASO 1-------#####################
# jogador1 = Jogador(jogo,nome="jogador1",f=jogador_avanco_F4)
# jogador2 = Jogador(jogo,nome="jogador2",f=random_player)
# r1 =um_jogo(jogo,jogador1, jogador2, 1, verbose = True)
# print("Vencedor do jogo: "+ r1.nome + " , com as peças "+r1.cor)



## FAZ UM JOGO ENTRE O JOGADOR 1 E O JOGADOR 2 UTILIZANDO A FUNCAO
## DE AVALIACAO F4 E A FUNCAO DE JOGADOR HUMANO 
######################----CASO 2-------#####################
#jogador1 = Jogador(jogo,nome="jogador1",f=jogador_avanco_F4)
#jogador2 = Jogador(jogo,nome="jogador2",f=query_player)
# r1 =um_jogo(jogo,jogador1, jogador2, 1, verbose = True)
# print("Vencedor do jogo: "+ r1.nome + " , com as peças "+r1.cor)



## FAZ UM PAR DE JOGOS ENTRE O JOGADOR 1 E O JOGADOR 2 UTILIZANDO A FUNCAO
## DE AVALIACAO F4 E A FUNCAO DE AVALIACAO F1 (DADA PELOS PROFESSORES)
######################----CASO 3-------#####################
# jogador1 = Jogador(jogo,nome="jogador1",f=jogador_avanco_F4)
# jogador2 = Jogador(jogo,nome="jogador2",f=jogador_avanco_F1)
# r1,r2 = um_par_de_jogos(jogo,jogador1, jogador2, 1, verbose = True)
# print("Vencedor do 1º jogo: "+ r1.nome + " , com as peças "+r1.cor)
# print("Vencedor do 2º jogo: "+ r2.nome+ " , com as peças "+r2.cor)
# print()



## FAZ UM PAR DE JOGOS ENTRE O JOGADOR 1 E O JOGADOR 2 UTILIZANDO A FUNCAO
## DE AVALIACAO F4 E A FUNCAO DE AVALIACAO F2
######################----CASO 4-------#####################
# jogador1 = Jogador(jogo,nome="jogador1",f=jogador_avanco_F4)
# jogador2 = Jogador(jogo,nome="jogador2",f=jogador_avanco_F2)
# r1,r2 = um_par_de_jogos(jogo,jogador1, jogador2, 1, verbose = True)
# print("Vencedor do 1º jogo: "+ r1.nome + " , com as peças "+r1.cor)
# print("Vencedor do 2º jogo: "+ r2.nome+ " , com as peças "+r2.cor)
# print()



## 10 PAR DE JOGOS ENTRE O JOGADOR 1 E O JOGADOR 2 UTILIZANDO A FUNCAO
## DE AVALIACAO F4 E A FUNCAO DE AVALIACAO F7
######################----CASO 5-------#####################
# jogador1 = Jogador(jogo,nome="jogador1",f=jogador_avanco_F4)
# jogador2 = Jogador(jogo,nome="jogador2",f=jogador_avanco_F7)
# r1, r2, r3, r4, r5 ,r6, r7 = n_pares_de_jogos(jogo,10,jogador1, jogador2, 1, verbose = True)
# print("Vistórias de "+ r6.nome + " com as peças Brancas: "+str(r1))
# print("Vistórias de "+ r6.nome + " com as peças Pretas: "+str(r2))
# print("Vistórias de "+ r7.nome + " com as peças Brancas: "+str(r3))
# print("Vistórias de "+ r7.nome + " com as peças Pretas: "+str(r4))
# print("Empates: "+str(r5))
# print()




## 10 PAR DE JOGOS ENTRE O JOGADOR 1 E O JOGADOR 2 UTILIZANDO A FUNCAO
## DE AVALIACAO F4 E A FUNCAO DE AVALIACAO F3
######################----CASO 6-------#####################
# jogador1 = Jogador(jogo,nome="jogador1",f=jogador_avanco_F4)
# jogador2 = Jogador(jogo,nome="jogador2",f=jogador_avanco_F3)
# r1, r2, r3, r4, r5 ,r6, r7 = n_pares_de_jogos(jogo,10,jogador1, jogador2, 1, verbose = True)
# print("Vistórias de "+ r6.nome + " com as peças Brancas: "+str(r1))
# print("Vistórias de "+ r6.nome + " com as peças Pretas: "+str(r2))
# print("Vistórias de "+ r7.nome + " com as peças Brancas: "+str(r3))
# print("Vistórias de "+ r7.nome + " com as peças Pretas: "+str(r4))
# print("Empates: "+str(r5))
# print()



## 10 PAR DE JOGOS ENTRE O JOGADOR 1 E O JOGADOR 2 UTILIZANDO A FUNCAO
## DE AVALIACAO F4 E A FUNCAO DE AVALIACAO F2 COM NIVEL A 3
######################----CASO 6-------#####################
# jogador1 = Jogador(jogo,nome="jogador1",f=jogador_avanco_F4)
# jogador2 = Jogador(jogo,nome="jogador2",f=jogador_avanco_F2)
# r1, r2, r3, r4, r5 ,r6, r7 = n_pares_de_jogos(jogo,10,jogador1, jogador2, 3, verbose = True)
# print("Vistórias de "+ r6.nome + " com as peças Brancas: "+str(r1))
# print("Vistórias de "+ r6.nome + " com as peças Pretas: "+str(r2))
# print("Vistórias de "+ r7.nome + " com as peças Brancas: "+str(r3))
# print("Vistórias de "+ r7.nome + " com as peças Pretas: "+str(r4))
# print("Empates: "+str(r5))
# print()

