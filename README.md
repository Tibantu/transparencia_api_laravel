# API TRANSFERENCIA 

## ROTAS
formato da resposta
<pre>
	{
		"message": `String`,
		"status":  `Number`,
		"data": `Array<Any> | Object`
	}
</pre>
### centralidade
`GET` /centralidades :

	- status: 200
	- message: null;
	- data: `Array<Centralidade>`
	
`POST` /centralidades:
	- status: 201
	- message: "centralidade criada com sucesso"
	- data: null
	