import { Input } from "@/ui/Fields";
import { EyeIcon, EyeOffIcon } from "@heroicons/react/outline";
import { useEffect, useState } from "react";

interface IPasswordInterface {
  label: string | JSX.Element;
  name: string;
  error?: string;
}

const PasswordField = (props: IPasswordInterface) => {
  const [eyeOpen, setEyeOpen] = useState(false);
  const clickedEye = () => {
    const input = document.querySelector("#id-input-password");
    input?.setAttribute("type", eyeOpen ? "password" : "text");
    setEyeOpen(!eyeOpen);
  };
  useEffect(() => {}, [eyeOpen]);
  return (
    <>
      <Input
        name={props.name}
        type={"password"}
        label={props.label}
        error={props.error}
        className="pr-12"
        disable={{ errorIcon: true }}
        append={
          <div
            className="absolute right-0 bg-transparent p-3 w-[3.5rem] rounded-r-lg cursor-pointer"
            onClick={clickedEye}
          >
            {eyeOpen ? (
              <EyeIcon className="text-gray-500" onClick={clickedEye} />
            ) : (
              <EyeOffIcon className="text-gray-500" onClick={clickedEye} />
            )}
          </div>
        }
      />
    </>
  );
};

export default PasswordField;
