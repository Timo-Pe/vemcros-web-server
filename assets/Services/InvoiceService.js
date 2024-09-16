import { URL } from "../constantes";

export const getShowInvoice = async (idInvoice) => {
  try {
    const response = await fetch(`${URL}/invoices/${idInvoice}`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("There was an error with the invoice service:", error);
    throw error;
  }
};
