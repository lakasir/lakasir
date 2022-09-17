import { useAuthApi } from "@/api/auth";
import { IFormLoginRequest, ILogiResponse } from "lib/models/auth";
import { ErrorResponse, Response } from "@/models/response";
import { useRouter } from "next/router";
import { toast } from "react-hot-toast";
import { AxiosError } from "axios";

type ErrorHanlder = (error: ErrorResponse) => void;

export const useAuth = () => {
  const router = useRouter();
  const { loginAction } = useAuthApi();

  const login = async (form: IFormLoginRequest, errors: ErrorHanlder) => {
    const toastId = toast.loading('Loging in...');
    try {
      const loginResponse = await loginAction(form);
      if (loginResponse instanceof AxiosError) {
        throw loginResponse;
      }
      const loginData = loginResponse as Response<ILogiResponse>;
      if (!loginData.success) {
        throw new Error(loginData.message);
      }
      toast.success('Login success');
      // save token auth in local storage
      localStorage.setItem('token', loginData.data.token);
      router.push("/menu");
    } catch (e) {
      if (e instanceof AxiosError) {
        const error = e as AxiosError<ErrorResponse>;
        errors(error.response?.data || {
          message: "",
          errors: {},
        });
        toast.error(error.message);
      }
    }
    toast.dismiss(toastId);
  }

  return {
    login,
  }
}
