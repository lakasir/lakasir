import { NextPage } from "next";
import PasswordField from "../../components/Auth/PasswordField";
import Button from "../../components/Ui/Buttons/Button";
import Form from "../../components/Ui/Fields/Form";
import Layout from "../../components/Ui/Layout";

const ForgotPassword: NextPage = () => {
  return (
    <Layout nosavearea>
      <div className="grid gap-16">
        <p className="flex justify-center items-end h-56 text-[32px] font-semibold">
          Forgot Password
        </p>
        <Form
          className="space-y-5"
          initialValue={{
            password: "",
            confirm_password: "",
          }}
          onSubmit={() => console.log("ok")}
        >
          {() => (
            <>
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
                name={"confirm_password"}
              />
              <Button className="w-full py-4">Confirm</Button>
            </>
          )}
        </Form>
      </div>
    </Layout>
  );
};

export default ForgotPassword;
