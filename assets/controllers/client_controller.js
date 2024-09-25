import { Controller } from "@hotwired/stimulus";
import { DataTable } from "simple-datatables";
import { ModalView } from "../class/ModalView";
import { getShowClient } from "../Services/ClientService";

export default class extends Controller {
  connect() {
    this.modalElement = document.getElementById("modal_view_client");
    this.modal = new ModalView(this.modalElement);
    this.initDatatableClient(); // initialize the datatable
    this.modal.updateOnHide(() => this.cleanModalClient());
  }

  initDatatableClient() {
    if (
      document.getElementById("table-clients") &&
      typeof DataTable !== "undefined"
    ) {
      new DataTable("#table-clients", {
        perPage: 15,
        tableRender: (_data, table, type) => {
          if (type === "print") {
            return table;
          }
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

  async openModalViewClient(event) {
    const idClient = event.currentTarget.dataset.idClient;

    if (!idClient) {
      return;
    }
    try {
      const dataClient = await getShowClient(idClient);
      this.modal.dataInView = dataClient;

      this.modal.setDataInModal(this.modal.dataInView, "clients");
      this.modal.setDataInSubTable(this.modal.dataInView.invoices, "invoices");

      this.modal.show();
    } catch (error) {
      console.error("There was an error with the modal view service:", error);
    }
  }
  cleanModalClient() {
    const DOMClient = document.getElementById("client_infos");
    const DOMInvoiceInfos = document.getElementById("invoice_infos");
    const DOMInvoiceTab = document.getElementById("modal_invoices_body");

    DOMInvoiceTab.innerHTML = "";
    DOMClient.classList.replace("w-3/6", "w-full");
    DOMInvoiceInfos.classList.add("hidden");
  }

  openDetailsInvoice(event) {
    const idInvoice = event.currentTarget.querySelector("[data-id]").dataset.id;

    const detailsInvoice = this.modal.dataInView.invoices.find(
      (invoice) => invoice.id == idInvoice
    );

    if (detailsInvoice) {
      this.modal.setDataInModal(detailsInvoice, "invoices");
    }

    const DOMClient = document.getElementById("client_infos");
    const DOMInvoice = document.getElementById("invoice_infos");

    DOMClient.classList.replace("w-full", "w-3/6");
    DOMInvoice.classList.remove("hidden");
  }
}
