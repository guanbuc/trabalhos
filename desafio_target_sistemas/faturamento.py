import json

caminho_arquivo = './Arquivo 1.json'

with open(caminho_arquivo, 'r', encoding='utf-8') as arquivo:
    dados = json.load(arquivo)

    soma = 0

    for i in dados:
        soma += i['valor']

    #print(soma)

    faturamentoPorEstado = {
        'SP': 67836.43,
        'RJ': 36678.66,
        'MG': 29229.88,
        'ES': 27165.48,
        'OUTROS': 19849.53
    }

def percentual(num):
    return (num / soma) * 100

percentualPorEstado = {}

for i in faturamentoPorEstado:
    percentualPorEstado[i] = percentual(faturamentoPorEstado[i])

print(percentualPorEstado)