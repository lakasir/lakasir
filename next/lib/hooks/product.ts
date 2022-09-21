import { useProductApi } from "@/api/product";
import {
  IProductResponse,
  IproductFormRequest,
  IProductFormResponse,
} from "@/models/product";
import { ErrorResponse, Response } from "@/models/response";
import axios from "@/utils/axios";
import { AxiosError } from "axios";
import { useRef } from "react";
import toast from "react-hot-toast";

export const useProduct = () => {
  const { getProductAction, deleteProductAction, getDetailProductAction } = useProductApi();
  const dataFetchRef = useRef(false);

  const getProduct = async (): Promise<Response<IProductResponse[]> | boolean> => {
    if (dataFetchRef.current) return false;
    dataFetchRef.current = true;
    const toastId = toast.loading("Get product...");
    try {
      const response = await getProductAction();
      toast.dismiss(toastId);
      return response;
    } catch (error) {
      toast.error("Failed to get product", { id: toastId });
      throw error;
    }
  };

  const getDetailProduct = async (id: number): Promise<Response<IProductResponse> | boolean> => {
    if (dataFetchRef.current) return false;
    dataFetchRef.current = true;
    const toastId = toast.loading("Get detail product...");
    try {
      const response = await getDetailProductAction(id);
      toast.dismiss(toastId);
      return response;
    } catch (error) {
      toast.error("Failed to get detail product", { id: toastId });
      throw error;
    }
  };

  const deleteProduct = async (id: number) => {
    const toastId = toast.loading("Deleting product...");
    try {
      await deleteProductAction(id);
      dataFetchRef.current = false;
      toast.dismiss(toastId);
    } catch (error) {
      toast.error("Failed to delete product", { id: toastId });
      throw error;
    }
  };

  return {
    getProduct,
    getDetailProduct,
    deleteProduct,
  };
};
