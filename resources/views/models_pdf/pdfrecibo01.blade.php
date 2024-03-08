<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDF</title>
<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

.page {
  padding: 0 15px;
}

.page-header,
.frame-header {
  display: flex;
  justify-content: space-between;
  padding: 10px 10px;
}

.frame-header-left {
  justify-content: left;
}

.frame-align-right {
  justify-content: right;
}

.frame-align-center {
  justify-content: center;
}

.frame-align-right {
  align-self: flex-end;
}

.page-table-body {
  display: flex;
  flex-direction: column;
  margin-top: 50px;
}

.page-table-body>table {
  width: 100%;
}

.page-table-footer,
.page-table-body>table thead {
  background-color: #aaa;
}

.page-table-body>table th,
.page-table-body>table td {
  padding: 8px;
  text-align: center;
}

.frame,.frame-obs {
  border: 1px solid #333;
  width: 100%;
}
.frame-obs-header{
padding: 10px;
}

.frame-header {
  background-color: #aaa;
}

.frame-body {
  margin-top: 10px;
  margin-bottom: 20px;
}

.frame-body,
.frame-footer {
  padding: 8px;
}

.page-body-entities {
  display: flex;
  gap: 10px;
}

.frame-table-container {
  display: flex;
  gap: 10px;
  margin-top: 10px;

}

.page-table {
  width: 100%;
}


</style>
</head>

<body>
  <div class="page">
    <header class="page-header">
      <div class="page-header-left"></div>
      <div class="page-header-right">
        <strong>RECIBO N. 10/03/2020</strong>
      </div>
    </header>
    <main class="page-body">
      <div class="page-body-entities">
        <div class="frame">
          <div class="frame-header frame-align-left">
            <strong>ENTIDADE</strong>
          </div>
          <div class="frame-body">
            <span>COMISÃO DE MORADORES DA CENTRALIDADE DO CEKELE</span>
          </div>
          <div class="frame-footer">
            <span>Bloco 2, Rua 01, Prédio 29</span>
          </div>
        </div>
        <div class="frame">
          <div class="frame-header">
            <div class="frame-header-left">
              <strong>CONDÓMINIO</strong>
            </div>
            <div class="frame-header-right"></div>
          </div>
          <div class="frame-body"></div>
        </div>

      </div>
      <div class="page-table-body">
        <table>
          <thead>
            <th>#</th>
            <th>FATURA</th>
            <th>DESCRIÇÃO</th>
            <th>VALOR DO DOCUMENTO </th>
            <th>VALOR PAGO</th>
            <th>PENDENTE</th>
          </thead>
          <tbody>
            <tr>
              <td>001</td>
              <td>FAT-001/29-02-2020</td>
              <td>Taxa de manutenção</td>
              <td>4.500,00</td>
              <td>4.000,00</td>
              <td>500,00</td>
            </tr>

          </tbody>
        </table>
        <div class="page-table-footer">
          footer
        </div>
        <div class="frame-table-container">
          <div class="frame-obs">
            <div class="frame-obs-header frame-align-left">
				<strong>Observações</strong>
            </div>
			<div class="frame-body">
				Texto Texto Texto Texto
			</div>
			<div class="frame-footer"></div>
          </div>
          <div class="frame frame-total">
            <div class="frame-header frame-align-center">
              <strong>TOTAL PAGO</strong>
            </div>
            <div class="frame-body">

			</div>
            <div class="frame-footer">

            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>
