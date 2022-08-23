import Link from "next/link";
import { useState } from "react";
import Button from "./Button";

interface IOptionAction {
  label: string;
  icon: JSX.Element | string;
  onClick: () => void;
}

interface IFloatingActionButtonInterface {
  options: IOptionAction[];
}

const FloatingActionButton = (props: IFloatingActionButtonInterface) => {
  const [show, setShow] = useState({
    option: false,
  });
  return (
    <div className="flex justify-between items-end fixed bottom-0 w-11/12 right-4">
      <div>
        {show.option ? (
          <div id="action-option" className="space-y-6 mb-6">
            {props.options.map((el, index) => (
              <div className="flex justify-start items-center" key={index}>
                <Button
                  className="w-14 rounded-full flex justify-center items-center drop-shadow-2xl h-14 bg-red-500 my-0"
                  onClick={el.onClick}
                >
                  {el.icon}
                </Button>
                <div className="ml-3">
                  <p className="px-1 py-0.5 bg-black opacity-70 text-white rounded-sm text-lg">
                    {el.label}
                  </p>
                </div>
              </div>
            ))}
          </div>
        ) : (
          ""
        )}
        <div>
          <Button
            className="w-14 py-4 rounded-xl flex justify-center items-center drop-shadow-2xl h-14"
            onClick={() => setShow({ option: !show.option })}
          >
            <img
              src={"./../assets/icons/Single Choice.svg"}
              width="30"
              height="30"
            />
          </Button>
        </div>
      </div>
      <Link href={"/menu/product/add"}>
        <Button className="w-4/5 py-4 rounded-xl drop-shadow-2xl text-lg font-semibold h-14 absolute right-0">
          Add Product
        </Button>
      </Link>
    </div>
  );
};

export default FloatingActionButton;
