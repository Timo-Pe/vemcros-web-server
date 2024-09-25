import { URL } from "../constantes";

export const getShowClient = async (idClient) => {
  try {
    const response = await fetch(`${URL}/clients/${idClient}`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("There was an error with the client service:", error);
    throw error;
  }
};
