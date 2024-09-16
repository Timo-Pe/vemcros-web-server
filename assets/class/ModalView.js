import { Modal } from "flowbite";

export class ModalView extends Modal {
  constructor(modalDomElement) {
    super(modalDomElement);
    this.modalDOMElement = modalDomElement;
    this.delaisPayment = 7;
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
   * Set the data in the modal view
   * @param {object} data - The data to set in the modal
   * @param {string} entityName - The entity name (e.g. "invoice", "client")
   * @param {HTMLElement} [parentNode=this.modalDOMElement] - The parent node of the fields
   * @returns {HTMLElement} The parent node
   */
  setDataInModal(data, entityName, parentNode = this.modalDOMElement) {
    const fieldsDOM = parentNode.querySelectorAll(
      ".modal_" + entityName + "_field"
    );
    if (fieldsDOM) {
      fieldsDOM.forEach((field) => {
        const fieldName = field.getAttribute("data-field");
        if (data[fieldName] !== undefined) {
          if (fieldName.toLowerCase().includes("date")) {
            field.innerHTML = this.convertToDate(data[fieldName]);
          } else {
            field.innerHTML = data[fieldName];
          }
        } else {
          field.innerHTML = "---";
        }
      });

      return parentNode;
    } else {
      return;
    }
  }

  /**
   * Fill a table with data. The table must have the following structure
   * <table id="modal_<entityName>_body">
   *   <tbody>
   *     <template id="tmpl_row_<entityName>">
   *       <tr>
   *         <td data-field="field1">field1</td>
   *         <td data-field="field2">field2</td>
   *       </tr>
   *     </template>
   *   </tbody>
   * </table>
   * The entityName must be the same as the one used in setDataInModal
   * @param {Array<Object>} data - array of objects to display in the table
   * @param {String} entityName - name of the entity
   */
  setDataInSubTable(data, entityName) {
    const bodyDOM = this.modalDOMElement.querySelector(
      "#modal_" + entityName + "_body"
    );
    const tmplRowDOM = this.modalDOMElement.querySelector(
      "#tmpl_row_" + entityName
    );
    const nbModal = this.modalDOMElement.querySelector(
      ".modal_" + entityName + "_field[data-field='modal_nb']"
    );
    if (data && data.length > 0) {
      for (const key of data) {
        const rowDOM = tmplRowDOM.content.cloneNode(true);
        const row = this.setDataInModal(key, entityName, rowDOM);

        bodyDOM.appendChild(row);
      }
      nbModal.innerHTML = data.length;

      this.toggledAccordion(nbModal, false);
    } else {
      nbModal.innerHTML = 0;
      this.toggledAccordion(nbModal, true);
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
        daysRemaining + " jours / " + this.delaisPayment + " jours";
      nextAlertDOM.textContent = "---";
    } else {
      const pastDueMilliseconds = Math.abs(remainingMilliseconds);
      const daysPastDue = Math.floor(
        pastDueMilliseconds / (1000 * 60 * 60 * 24)
      );

      const nextAlertInDays =
        this.delaisPayment - (daysPastDue % this.delaisPayment);

      remainingDayDOM.textContent = "Délais dépassé";
      nextAlertDOM.textContent = nextAlertInDays + " jours";
    }
  }
}
