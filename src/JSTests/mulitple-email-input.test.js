/**
 * @jest-environment jsdom
 */
import multipleEmailInput from '../multiple-email-input.js';

it('test for template file', ()=>{
    var retrievedTemplate = multipleEmailInput.template;
    expect(typeof retrievedTemplate).toBe('string');
    var htmlEscaped = retrievedTemplate.replace(/<\/?[^>]+(>|$)/g, "");
    expect( htmlEscaped === retrievedTemplate ).toBeFalsy();
})

it('test for data', ()=>{
    var expectedData = { hide:false,
        emailList: [],
        recipientList:'',
        settings :{
            color: "#343a40",
            textColor: "#000000",
            fontAwesome: false,
        },
        styleList:'',
        styleListContainer:'',
    }
    var retrievedData = multipleEmailInput.data();
    expect(expectedData).toEqual(retrievedData);
})

it('test for methods',()=>{
    var module = {
        emailList:['abc@gmail.com', 'xyz@gmail.com'],
        printEmailList(){
            this.printEmailFunctionCalled = true;
        },
        printEmailFunctionCalled:false,
        recipientList:[],
        $refs:{
            global_email_recipients:{
                value:''
            }
        },
        uniqueEmails(emails){
            return emails;
        },
        e:{
            target:{
                getAttribute(){
                    return 1;
                }
            }
        }
    }
    var boundUniqueEmails = multipleEmailInput.methods.uniqueEmails.bind(module);
    expect(boundUniqueEmails()).toEqual(['abc@gmail.com', 'xyz@gmail.com']);

    var container = document.createElement('div');
    container.innerHTML = `
		<input type="hidden" id="email" value="abc@gmail.com" />
        <input type="hidden" id="wpadcenter_email_recipient_warning_data" data-empty="empty" data-invalid="Invalid"/>
	`;
    document.body.appendChild(container);
    var boundEmailKeyUp = multipleEmailInput.methods.emailKeyUp.bind(module);
    boundEmailKeyUp({keyCode:188,which:188})
    expect(module.printEmailFunctionCalled).toBeTruthy();

    var boundPrintEmailList = multipleEmailInput.methods.printEmailList.bind(module);

    boundPrintEmailList('pqr@gmail.com');
    expect(module.recipientList).toEqual('abc@gmail.com,xyz@gmail.com,pqr@gmail.com');
    expect(module.emailList ).toEqual(['abc@gmail.com', 'xyz@gmail.com', 'pqr@gmail.com']);
    expect(module.$refs.global_email_recipients.value ).toEqual('abc@gmail.com,xyz@gmail.com,pqr@gmail.com');

    var boundHexToRgbA = multipleEmailInput.methods.hexToRgbA.bind(module);
    expect(boundHexToRgbA('#00FFFF')).toEqual('rgba(0,255,255,0.08)');

    var boundRemoveEmail =  multipleEmailInput.methods.removeEmail.bind(module);
    boundRemoveEmail(module.e);
    expect(module.emailList).toEqual([ 'abc@gmail.com', 'pqr@gmail.com' ]);
    expect(module.$refs.global_email_recipients.value).toEqual('abc@gmail.com,pqr@gmail.com');

})

