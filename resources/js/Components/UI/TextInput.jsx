export default function TextInput({ className = '', ...props }) {
    return <input className={`text-input ${className}`.trim()} {...props} />;
}
