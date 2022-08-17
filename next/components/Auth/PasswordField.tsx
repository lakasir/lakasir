import { EyeIcon } from "@heroicons/react/solid";
import Input from "../Ui/Fields/Input";

const PasswordField = () => {
  return (
    <>
      <Input
        name={"password"}
        type={"password"}
        label="Password"
        className="pr-12"
        disable={{errorIcon: true}}
        error="Error"
        append={<div className="absolute right-0 bg-transparent p-3 w-[3.5rem] rounded-r-lg cursor-pointer" onClick={() => console.log("OK")}><EyeIcon/></div>}
      />
    </>
  );
};

export default PasswordField;
