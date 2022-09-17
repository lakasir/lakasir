import PasswordField from "@/components/Auth/PasswordField";
import { useAuth } from "@/hooks/auth";
import { IFormRegisterRequest } from "@/models/auth";
import { ErrorResponse } from "@/models/response";
import { Button } from "@/ui/Buttons";
import { Checkbox, Form, Input } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import Link from "next/link";
import { FormEvent } from "react";


const Register: NextPage = () => {
  const { register } = useAuth();
  const submitRegister = async (_: FormEvent, values: IFormRegisterRequest) => {
    register(values, (error: ErrorResponse) => {
      console.log(error);
    })
  }
  return (
    <Layout nosavearea>
      <div className="grid gap-12">
        <p className="flex justify-center items-end h-40 text-[32px] font-semibold">
          Sign Up <span className="ml-2 text-lakasir-primary"> LAKASIR</span>
        </p>
        <Form
          className="space-y-5"
          initialValue={{
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
          }}
          onSubmit={submitRegister}
        >
          {() => (
            <>
              <Input
                name={"name"}
                type={"text"}
                label={
                  <>
                    Full Name<span className="text-red-500">*</span>
                  </>
                }
              />
              <Input
                name={"email"}
                type={"text"}
                label={
                  <>
                    Username or Email<span className="text-red-500">*</span>
                  </>
                }
              />
              <PasswordField
                label={
                  <>
                    Password<span className="text-red-500">*</span>
                  </>
                }
                name={"password"}
              />
              <PasswordField
                label={
                  <>
                    Confirm Password<span className="text-red-500">*</span>
                  </>
                }
                name={"password_confirmation"}
              />
              <Checkbox
                name={"remember_me"}
                label={
                  <span className="font-medium">
                    By creating an account, you agree to our <br />{" "}
                    <Link href={"/terms"}>
                      <a className="text-lakasir-primary">
                        Terms and Conditions
                      </a>
                    </Link>
                  </span>
                }
              />
              <Button className="w-full py-4">Register</Button>
            </>
          )}
        </Form>
        <div className="mb-20">
          <p className="font-medium text-center">
            You have an account?{" "}
            <Link href="/auth/login">
              <a className="text-lakasir-primary">Sign In</a>
            </Link>
          </p>
        </div>
      </div>
    </Layout>
  );
};

export default Register;
