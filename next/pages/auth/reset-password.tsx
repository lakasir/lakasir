import { useAuthApi } from "@/api/auth";
import PasswordField from "@/components/Auth/PasswordField";
import { useAuth } from "@/hooks/auth";
import { IFormResetPasswordRequest } from "@/models/auth";
import { ErrorResponse } from "@/models/response";
import { Button } from "@/ui/Buttons";
import { Form } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import { useRouter } from "next/router";
import { FormEvent, useState } from "react";

interface IErrorResetPasswordResponse {
  email?: string;
  password?: string;
  password_confirmation?: string;
  token?: string;
}



const ForgotPasswordToken: NextPage = () => {
  // get token and email from the url
  const router = useRouter();
  const { token, email } = router.query;
  const [errors, setErrors] = useState<IErrorResetPasswordResponse>({});
  const { resetPassword } = useAuth();
  const resetPasswordSubmit = async (_: FormEvent, values: IFormResetPasswordRequest) => {
    values.token = token as string;
    values.email = email as string;
    resetPassword(values, (error: ErrorResponse) => {
      setErrors({
        email: error.errors.email ? error.errors.email[0] : "",
        password: error.errors.password ? error.errors.password[0] : "",
        password_confirmation: error.errors.password_confirmation ? error.errors.password_confirmation[0] : "",
        token: error.errors.token ? error.errors.token[0] : "",
      });
    })
  }
  return (
    <Layout nosavearea>
      <div className="grid gap-16">
        <p className="flex justify-center items-end h-56 text-[32px] font-semibold">
          Reset Password
        </p>
        <Form
          className="space-y-5"
          initialValue={{
            password: "",
            password_confirmation: "",
          }}
          onSubmit={resetPasswordSubmit}
        >
          {() => (
            <>
              <PasswordField
                error={errors.password}
                label={
                  <>
                    Password<span className="text-red-500">*</span>
                  </>
                }
                name={"password"}
              />
              <PasswordField
                error={errors.password_confirmation}
                label={
                  <>
                    Confirm Password<span className="text-red-500">*</span>
                  </>
                }
                name={"password_confirmation"}
              />
              <Button className="w-full py-4">Confirm</Button>
            </>
          )}
        </Form>
      </div>
    </Layout>
  );
};

export default ForgotPasswordToken;
