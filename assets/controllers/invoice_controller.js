import { Controller } from "@hotwired/stimulus";
import { DataTable } from "simple-datatables";
import { getShowInvoice } from "../Services/InvoiceService";
import { ModalView } from "../class/ModalView";
import { INVOICE_STATUS } from "../constantes";

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

    if (!idInvoice) {
      return;
    }
    try {
      const dataInvoice = await getShowInvoice(idInvoice);

      this.modal.setDataInModal(dataInvoice, "invoice");
      this.modal.setDataInModal(dataInvoice.clients, "clients");

      console.log(dataInvoice);
      this.modal.setDataInSubTable(dataInvoice.alerts, "alerts");
      this.modal.setDataInSubTable(dataInvoice.payments, "payments");

      this.addRemainingDays(dataInvoice.status, dataInvoice.due_date);
      this.changeBadgeStatusColor(dataInvoice.status);

      this.modal.show();
    } catch (error) {
      console.error("There was an error with the modal view service:", error);
    }
  }

  addRemainingDays(status, due_date) {
    const optionnalGroups = this.modal.getAllOptionnalGroups();

    const remaining = optionnalGroups.find(
      (group) => group.groupName === "remaining"
    );

    if (remaining && remaining.conditionalField === "status") {
      if (status === INVOICE_STATUS.UNPAID) {
        this.modal.setRemainingDays(due_date, "invoice");
        this.modal.changeGroupVisibility(remaining.groupName, "show");
      } else {
        this.modal.changeGroupVisibility(remaining.groupName, "hide");
      }
    }
  }

  changeBadgeStatusColor(status) {
    const invoiceStatus = this.modal.modalDOMElement.querySelector(
      ".modal_invoice_field[data-field='status']"
    );

    if (status === INVOICE_STATUS.UNPAID) {
      invoiceStatus.classList.add("bg-red-100", "text-red-800");
      invoiceStatus.classList.remove("bg-green-100", "text-green-800");
    } else {
      invoiceStatus.classList.add("bg-green-100", "text-green-800");
      invoiceStatus.classList.remove("bg-red-100", "text-red-800");
    }
  }
}
