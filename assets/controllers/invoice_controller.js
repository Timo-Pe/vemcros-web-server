import { Controller } from "@hotwired/stimulus";
import { DataTable } from "simple-datatables";
import { getShowInvoice } from "../Services/InvoiceService";
import { ModalView } from "../class/ModalView";

export default class extends Controller {
  connect() {
    this.modalElement = document.getElementById("modal_view_invoice");
    this.initDatatableInvoice();
    this.modal = new ModalView(this.modalElement);
  }

  initDatatableInvoice() {
    if (
      document.getElementById("filter-table") &&
      typeof DataTable !== "undefined"
    ) {
      const dataTable = new DataTable("#filter-table", {
        perPage: 20,
        sortable: true,
        tableRender: (_data, table, type) => {
          if (type === "print") {
            return table;
          }
          //translate in french label
          const dropdown = document.querySelector(".datatable-dropdown");
          const select = dropdown.querySelector("select");
          dropdown.innerHTML = "Entrées par page";
          dropdown.prepend(select);

          // Hide search bar
          const searchBar = document.querySelector(".datatable-input");
          searchBar.classList.add("hidden");

          const tHead = table.childNodes[0];
          const filterHeaders = {
            nodeName: "TR",
            attributes: {
              class: "search-filtering-row",
            },
            childNodes: tHead.childNodes[0].childNodes.map((_th, index) => ({
              nodeName: "TH",
              childNodes: [
                {
                  nodeName: "INPUT",
                  attributes: {
                    class: "datatable-input",
                    type: "search",
                    "data-columns": "[" + index + "]",
                  },
                },
              ],
            })),
          };
          tHead.childNodes.push(filterHeaders);
          return table;
        },
      });
    }
  }

  async openModalViewInvoice(event) {
    const idInvoice = event.currentTarget.dataset.idInvoice;
    console.log(idInvoice);
    if (!idInvoice) {
      return;
    }
    try {
      const dataInvoice = await getShowInvoice(idInvoice);

      this.modal.setDataInModal(dataInvoice, "invoice");
      this.modal.setDataInModal(dataInvoice.clients, "clients");
      this.modal.setOptionnalCalc(
        dataInvoice.due_date,
        dataInvoice.invoice_date
      );
      this.modal.setDataInSubTable(dataInvoice.alerts, "alerts");
      this.modal.setDataInSubTable(dataInvoice.payments, "payments");

      this.modal.show();
    } catch (error) {
      console.error("There was an error with the modal view service:", error);
    }
  }
}
