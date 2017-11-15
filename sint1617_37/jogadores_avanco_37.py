"""
Trabalho de Sistemas Inteligentes
Realizado por :
    Miguel Marques, nº47139
    Duarte Sousa, nº47164
    
"""

import alfabeta 

from jogo_avanco_37 import *


def vitoria_3peoes(jogador,estado) :
    obj = 3 if jogador=='pretas' else 1
    cond1 = obj in [x for (x,_) in estado.board[jogador]]
    cond2 = estado.board[JogoAvanco.outro_jogador(jogador)] == []

    return cond1 or cond2



def f_aval_avanco_F1(estado,jogador) :
    adversario = JogoAvanco.outro_jogador(jogador)
    if vitoria_3peoes(jogador,estado) :
        f = 2
    elif vitoria_3peoes(adversario,estado) :
        f = -2
    else :
        f = len(estado.board[jogador]) - len(estado.board[adversario])
    return f

def medDist(listt,objectivo):
    if len(listt)!=0:
        return sum(map(lambda x: objectivo-x[0],listt))/len(listt)
    return 0

def minT(listt,objectivo):
    result = sorted(map(lambda x: objectivo-x[0],listt))
    if result==[]:
        return -1
    return min(result)


def medDistPeças(listt,adversario,estado):
    soma = 0
    for i in listt:
        soma += medDist(estado.board[adversario],i[0])
    if len(listt)!=0:
            return soma/len(listt)
    return 0

def melhorLados(listt,adversario,estado):
    soma = 0
    for i in listt:
        soma += 3 if i[1] == (3 or 4 or 5) else 1
    return soma/len(listt)

def melhorMeio(listt):
    soma = 0
    for i in listt:
        soma += 3 if i[1] == (6 or 7 or 1 or 2) else 1
    return soma/len(listt)

def f_aval_avanco_F2(estado,jogador) :
    adversario = JogoAvanco.outro_jogador(jogador)
    if minT(estado.board[jogador],1 if jogador=='pretas' else 7) !=0:
        minimo = minT(estado.board[adversario],7 if jogador=='pretas' else 1)/minT(estado.board[jogador],1 if jogador=='pretas' else 7)
    else:
        minimo = -2

    if len(estado.board[adversario]) > 0:
        tamanho = (len(estado.board[jogador])/len(estado.board[adversario]))
    else:
        tamanho = -2

    return ((1/(medDist(estado.board[jogador],1 if jogador=='pretas' else 7)+ 0 if (medDist(estado.board[jogador],1 if jogador=='pretas' else 7)) != 0 else -1 )) * (1/(medDistPeças(estado.board[jogador],adversario,estado) + 0 if (medDistPeças(estado.board[jogador],adversario,estado)) != 0 else -1 )))*0.45 + (tamanho)*0.45 + (minimo)*0.1

def f_aval_avanco_F3(estado,jogador) :
    adversario = JogoAvanco.outro_jogador(jogador)
    #print((len(estado.board[jogador]) - len(estado.board[adversario])))
    return (medDist(estado.board[jogador],1 if jogador=='pretas' else 7)/((len(estado.board[jogador]) - len(estado.board[adversario]))+ 1 if (len(estado.board[jogador]) - len(estado.board[adversario]))!= -1 else -1 )) + medDistPeças(estado.board[jogador],adversario,estado)


def f_aval_avanco_F4(estado,jogador) :
    adversario = JogoAvanco.outro_jogador(jogador)
    premio=0
    obj = 1 if jogador=="pretas" else 7
    if obj in [x for (x,_) in estado.moves]:
        premio =20 
    if len(estado.board[adversario]) > 0:
            return (len(estado.board[jogador])/len(estado.board[adversario]))
    return 0

def f_aval_avanco_F5(estado,jogador) :
    adversario = JogoAvanco.outro_jogador(jogador)
    if minT(estado.board[jogador],1 if jogador=='pretas' else 7) !=0:
            return minT(estado.board[adversario],7 if jogador=='pretas' else 1)/minT(estado.board[jogador],1 if jogador=='pretas' else 7)
    return 0

def f_aval_avanco_F6(estado,jogador) :
    adversario = JogoAvanco.outro_jogador(jogador)
    if len(estado.board[adversario]) > 0 :
            return ((1/(medDist(estado.board[jogador],1 if jogador=='pretas' else 7)+ 1 if (medDist(estado.board[jogador],1 if jogador=='pretas' else 7)) != -1 else -1    )) * medDistPeças(estado.board[jogador],adversario,estado))*0.6 + (len(estado.board[jogador])/len(estado.board[adversario]))*0.4
    return 0

def f_aval_avanco_F7(estado,jogador) :
    adversario = JogoAvanco.outro_jogador(jogador)
    premio=0
    obj = 1 if jogador=="pretas" else 7
    if obj in [x for (x,_) in estado.moves]:
        premio =20 
    return (len(estado.board[jogador])/len(estado.board[adversario]) * len(estado.moves)) + premio

def jogador_avanco_F1(jogo,estado, nivel = 5) :
    return alfabeta.alphabeta_search(estado,jogo,nivel,eval_fn=f_aval_avanco_F1)

def jogador_avanco_F2(jogo,estado, nivel = 5) :
    return alfabeta.alphabeta_search(estado,jogo,nivel,eval_fn=f_aval_avanco_F2)

def jogador_avanco_F3(jogo,estado, nivel = 5) :
    return alfabeta.alphabeta_search(estado,jogo,nivel,eval_fn=f_aval_avanco_F3)

def jogador_avanco_F4(jogo,estado, nivel = 5) :
    return alfabeta.alphabeta_search(estado,jogo,nivel,eval_fn=f_aval_avanco_F4)

def jogador_avanco_F5(jogo,estado, nivel = 5) :
    return alfabeta.alphabeta_search(estado,jogo,nivel,eval_fn=f_aval_avanco_F5)

def jogador_avanco_F6(jogo,estado, nivel = 5) :
    return alfabeta.alphabeta_search(estado,jogo,nivel,eval_fn=f_aval_avanco_F6)

def jogador_avanco_F7(jogo,estado, nivel = 5) :
    return alfabeta.alphabeta_search(estado,jogo,nivel,eval_fn=f_aval_avanco_F7)


