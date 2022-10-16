import { useProductApi } from "@/api/product";
import {
  IProductResponse,
  IProductFormRequest,
  IProductFormResponse,
} from "@/models/product";
import { ErrorResponse, Response } from "@/models/response";
import { AxiosError } from "axios";
import { useRouter } from "next/router";
import { useRef } from "react";
import toast from "react-hot-toast";

type ErrorHanlder = (error: ErrorResponse) => void;

export const useProduct = () => {
  const {
    getProductAction,
    deleteProductAction,
    getDetailProductAction,
    updateProductAction,
    createProductAction,
  } = useProductApi();
  const dataFetchRef = useRef(false);
  const router = useRouter();

  const getProduct = async (): Promise<
    Response<IProductResponse[]> | boolean
  > => {
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

  const getDetailProduct = async (
    id: number
  ): Promise<Response<IProductResponse> | boolean> => {
    if (dataFetchRef.current) return false;
    dataFetchRef.current = true;
    const toastId = toast.loading("Get detail product...");
    try {
      const response = await getDetailProductAction(id);
      toast.dismiss(toastId);
      return response;
    } catch (error) {
      dataFetchRef.current = false;
      toast.error("Failed to get detail product", { id: toastId });
      throw error;
    }
  };

  const updateProduct = async (
    id: number,
    data: IProductFormRequest,
    setErrors: ErrorHanlder
  ): Promise<Response<IProductFormResponse> | boolean> => {
    if (dataFetchRef.current) return false;
    dataFetchRef.current = true;
    const toastId = toast.loading("Update product...");
    try {
      const response = await updateProductAction(id, data);
      toast.dismiss(toastId);
      dataFetchRef.current = false;
      router.push("/menu/product");
      return response;
    } catch (error) {
      dataFetchRef.current = false;
      toast.error("Failed to update product", { id: toastId });
      const axiosError = error as AxiosError<ErrorResponse>;
      if (axiosError.response?.data && axiosError.response?.status === 422) {
        setErrors(
          axiosError.response?.data || {
            message: "",
            errors: {},
          }
        );
      } else {
        setErrors({
          message: axiosError.message || "Failed to update product",
          errors: {},
        });
      }
      return {} as Response<IProductFormResponse>;
    }
  };

  const createProduct = async (
    data: IProductFormRequest,
    setErrors: ErrorHanlder
  ): Promise<Response<IProductFormResponse> | boolean> => {
    if (dataFetchRef.current) return false;
    dataFetchRef.current = true;
    const toastId = toast.loading("Create product...");
    try {
      const response = await createProductAction(data);
      toast.dismiss(toastId);
      dataFetchRef.current = false;
      router.push("/menu/product");
      return response;
    } catch (error) {
      dataFetchRef.current = false;
      toast.error("Failed to create product", { id: toastId });
      const axiosError = error as AxiosError<ErrorResponse>;
      if (axiosError.response?.data && axiosError.response?.status === 422) {
        setErrors(
          axiosError.response?.data || {
            message: "",
            errors: {},
          }
        );
      } else {
        setErrors({
          message: axiosError.message || "Failed to create product",
          errors: {},
        });
      }
      return {} as Response<IProductFormResponse>;
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
    updateProduct,
    createProduct,
  };
};
