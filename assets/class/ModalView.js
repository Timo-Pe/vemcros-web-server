import { Modal } from "flowbite";
import { MAX_DELAY_PAYMENT } from "../constantes";

export class ModalView extends Modal {
  constructor(modalElement) {
    super(modalElement);
    this._modalDOMElement = modalElement;
    this._dataInView = [];
  }

  /**
   * Convert a date (in milliseconds) to a string in the format "dd-mm-yyyy"
   * @param {number} date - The date to convert
   * @returns {string} The formatted date
   */
  convertToDate(date) {
    const dateValue = new Date(date);
    // Vérifier si c'est une date valide
    if (!isNaN(dateValue)) {
      // Formater la date dans le format souhaité : "dd-mm-yyyy"
      const formattedDate = dateValue.toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      });
      return formattedDate;
    } else {
      return "Date invalide";
    }
  }

  /**
   * Fill the modal with data
   * @param {Object} data - The data to fill the modal with
   * @param {string} entityName - The name of the entity (invoices or payments)
   * @param {HTMLElement} [parentNode=this.modalDOMElement] - The parent element of the modal
   * @param {number} [indexInTemplate] - The index to use for the data-index attribute
   * @returns {HTMLElement} The modified parent element
   */
  setDataInModal(
    data,
    entityName,
    parentNode = this.modalDOMElement,
    indexInTemplate = null
  ) {
    // Sélectionne tous les champs pertinents
    const fieldsDOM = parentNode.querySelectorAll(
      ".modal_" + entityName + "_field"
    );

    fieldsDOM.forEach((field) => {
      if (!field.hasAttribute("data-index")) {
        const fieldName = field.getAttribute("data-field");

        if (data[fieldName] !== undefined) {
          if (indexInTemplate) {
            field.dataset.index = indexInTemplate;
          }

          if (field.dataset.opt) {
            this.convertWithOptions(data[fieldName], field);
          } else {
            field.innerHTML = data[fieldName];
          }
        } else {
          field.innerHTML = "---";
        }
      }
    });

    return parentNode;
  }

  /**
   * Apply options to a field
   * @param {*} data - The data to apply the option to
   * @param {HTMLElement} field - The field to apply the option to
   * @returns {HTMLElement} The field
   */
  convertWithOptions(data, field) {
    const options = field.dataset.opt;

    switch (options) {
      case "convertToDate":
        data = this.convertToDate(data);
        field.innerHTML = data;
        break;
      case "idToField":
        field.dataset.id = data;
        field.innerHTML = data;
        break;
      default:
        return field;
    }
  }

  /**
   * Fill the sub-table with data
   * @param {Array} data - The data to fill the table with
   * @param {string} entityName - The name of the entity (invoices or payments)
   * @returns {HTMLElement} The sub-table body
   */
  setDataInSubTable(data, entityName) {
    // Sélectionner les éléments DOM nécessaires
    const bodyDOM = this.modalDOMElement.querySelector(
      "#modal_" + entityName + "_body"
    );
    const tmplRowDOM = this.modalDOMElement.querySelector(
      "#tmpl_row_" + entityName
    );
    const nbModal = this.modalDOMElement.querySelector(
      ".modal_" + entityName + "_field[data-field='modal_nb']"
    );

    bodyDOM.innerHTML = "";
    if (data && data.length > 0) {
      const fragment = document.createDocumentFragment();

      for (let i = 0; i < data.length; i++) {
        const rowDOM = tmplRowDOM.content.cloneNode(true);
        const row = this.setDataInModal(data[i], entityName, rowDOM, i + 1);
        fragment.appendChild(row);
      }

      bodyDOM.appendChild(fragment);

      if (nbModal) {
        nbModal.innerHTML = data.length;
      }
    } else {
      if (nbModal) {
        nbModal.innerHTML = 0;
      }
    }
  }
  /**
   * Toggle the accordion button
   * @param {HTMLElement} target - the target element
   * @param {Boolean} status - true to disable the button, false to enable it
   */
  toggledAccordion(target, status) {
    const btn = target.closest("button");
    const icon = btn.querySelector("svg");
    if (status) {
      btn.classList.add("bg-gray-100", "cursor-not-allowed");
      icon.classList.add("hidden");
      btn.disabled = true;
    } else {
      btn.classList.remove("bg-gray-100", "cursor-not-allowed");
      icon.classList.remove("hidden");
      btn.disabled = false;
    }
  }

  setOptionnalCalc(due_date, send_at) {
    const remainingDayDOM = this.modalDOMElement.querySelector(
      ".modal_invoice_field[data-field='remaining_days']"
    );

    const nextAlertDOM = this.modalDOMElement.querySelector(
      ".modal_invoice_field[data-field='next_alert']"
    );

    const remainingMilliseconds = new Date(due_date) - new Date();
    const daysRemaining = Math.floor(
      remainingMilliseconds / (1000 * 60 * 60 * 24)
    );

    if (daysRemaining >= 0) {
      remainingDayDOM.textContent =
        daysRemaining + " jours / " + MAX_DELAY_PAYMENT + " jours";
      nextAlertDOM.textContent = "---";
    } else {
      const pastDueMilliseconds = Math.abs(remainingMilliseconds);
      const daysPastDue = Math.floor(
        pastDueMilliseconds / (1000 * 60 * 60 * 24)
      );

      const nextAlertInDays =
        MAX_DELAY_PAYMENT - (daysPastDue % MAX_DELAY_PAYMENT);

      remainingDayDOM.textContent = "Délais dépassé";
      nextAlertDOM.textContent = nextAlertInDays + " jours";
    }
  }

  get options() {
    return this._options;
  }
  set options(options) {
    this._options = options;
  }
  get modalDOMElement() {
    return this._modalDOMElement;
  }
  set modalDOMElement(modalDOMElement) {
    this._modalDOMElement = modalDOMElement;
  }
  get dataInView() {
    return this._dataInView;
  }
  set dataInView(dataInView) {
    this._dataInView = dataInView;
  }
}
