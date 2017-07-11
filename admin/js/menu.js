
//========================����menu��==================;
function Menu(head,child,dir,speed,init_state,ext_on,ext_off)
{
	this.head = document.getElementById(head);//�˵�ͷ
	this.body = document.getElementById(child);//�˵���
	this.direction = dir;//�˵�����ķ���
	this.speed = speed;//�ٶ�
	this.ext_on = ext_on;//��չ�˵�չ������
	this.ext_off = ext_off;//��չ�˵��������
	this.init_state = init_state;//���ò˵��ĳ�ʼ״̬ true/false
	this.a = 20;//���ٶ�
//˽�ñ���;
	this._interval = false;
	this._last_state = false;
	this._size = false;
	this._temp = false;
	this._js = false;
	this._div = false;
	this._parent = false;
	this._parent_control = false;
	var self = this;
	var temp = new Array(null,null);//temp[0]������_off()�ã�temp[1]������_on()��
	
//=============================����=============================
//����¼�����
	this.click = function(e)
	{
		if (self._parent_control)
		{
			self._parent._control(self);
			return false;
		}
		else
		{
			Interval.clear(self._interval);
			if (self._last_state == false)
			{
				self._on();
				return false;
			}
			else
			{
				self._off();
				return false;
			}
		}
	}
	
//��ʼ��
	this.init = function()
	{
		this.head.onclick = this.click;
		this.head.onkeypress = function(e)
		{
			e||(e=window.event);
			if (!(e.keyCode ==32 || e.keyCode == 0))return;
			//alert(':)');
			self.click();
		}
		for(var i=0;i<this.body.childNodes.length;i++)
		{
			if (this.body.childNodes[i].nodeType==1)
			{
				this._div=this.body.childNodes[i];
				break;
			}
		}
		if (parseInt(this.body.style.height))//this.body.style.getPropertyCSSValue('height')this.body.currentStyle.height
		{
			this._size = parseInt(this.body.style.height);
		}
		else
		{
			this._size = this._div.offsetHeight;
		}
		switch (this.init_state)
		{
			case true:
				if (this.body.style.display == 'none')
				{
					//this._last_state = false;
					this._on();
				}
				else
				{
					this._last_state = true;
				}
				break;
			default://case false:
				if (this.body.style.display !='none')
				{
					this._last_state = true;
					this._off();
				}
				break;
		}
	}
//չ���˵�
	this._on = function()
	{
		if (self._last_state == false)
		{
			self._last_state = true;
			self.body.style.display="";
			temp[1] = self.a?2*parseInt(Math.sqrt(self.a*self._size))+1:self._size/5;
			if (isNaN(parseInt(self.body.style.height)))self.body.style.height="0px";
			if (self.ext_on)
			{
				self.ext_on(self.head,self.body)
			}
			self._interval = Interval.set(self._action_on,speed);
		}
		//setTimeout('slowon("'+self.body.id+'")',5)
	}
//����˵�
	this._off = function()
	{
		if (self._last_state == true)
		{
			self._last_state = false;
			//if (temp[0] == null)
			//{
				temp[0]=self.a?2*parseInt(Math.sqrt(self.a*self._size))+1:self._size/5;;
			//}
			if(isNaN(parseInt(self.body.style.height)))self.body.style.height = self._size+'px';
			if (self.ext_off)
			{
				self.ext_off(self.head,self.body)
			}
			self._interval = Interval.set(self._action_off,this.speed);
		}
	}
//���´�����
	this._action_on = function()
	{
		if (parseInt(self.body.style.height)+temp[1]>self._size)
		{
			self.body.style.height = self._size+'px';
			Interval.clear(self._interval);
		}
		else
		{
		self.body.style.height = parseInt(self.body.style.height)+temp[1]+'px';
		temp[1] +=self.a;
		}
	}
	this._action_off = function()
	{
		if(parseInt(self.body.style.height)-temp[0]<0)
		{
			Interval.clear(self._interval);
			self.body.style.display = "none";
		}
		else
		{
			self.body.style.height = parseInt(self.body.style.height)-temp[0]+'px';
			temp[0]-=self.a;
		}
	}
}
//meanu�����

//====================����Navbar�࣬��������һ��menu����===============================
function navbar(dir,a,speed,ext_on,ext_off)
{
	this.open_only_one = true;//����menu���κ�ʱ���Ƿ�ֻ��һ���ڿ�����true/false
	this.dir = dir;//menu��Ĺ������򣬼�Ȼ��һ��menu����Ӧ������ͬ�ķ���ɣ�
	this.a =a;//menu�������ٶ�
	this.speed =speed;//�����ٶ�
	this.ext_on = ext_on;//������չ�򿪺�������
	this.ext_off = ext_off;//��������չ����������
	this.menu_item = new Array();//menu��
	this._openning;//���ֻ�����һ���˵�������ͻ��¼��ǰ�򿪵Ĳ˵�
	this.open_all = function()//
	{
	};
	this.add = function (head,body)//���menu�ĺ���
	{
		var temp = new Menu(head,body,this.dir,this.speed,this.ext_on,this.ext_off);
		this.menu_item.push(temp);
	};
	this.init = function ()//Navbar�ĳ�ʼ��������������add��ɺ����
	{
		if(this.open_only_one == true)
		{//���ֻ����һ���򿪣���ô�������ò˵���ĵ�һ���˵�Ϊ��״̬
			if (this.menu_item.length>0)
			{
				with(this.menu_item[0])
				{
					init_state = true;
					_parent = this;//����menu�ĸ���Ϊ���Navbar
					_parent_control = true;//���ø��������Ʋ˵�
					init();
				}
				this._openning = this.menu_item[0];
			}
			for(var i = 1; i<this.menu_item.length;i++)
			{//���ó���һ����������˵�Ϊ�رգ�ͬʱ���ú���������
				with(this.menu_item[i])
				{
					init_state = false;
					_parent = this;
					init();
					_parent_control = true;
				}
			}
		}
		else
		{//���open_only_one == false ��ô������ʼ���˵�
			for(var i = 0;i<this.menu_item.length;i++)
			{
				this.menu_item.init();
			}
		}
	};
//������ӵĸ��׿��ƺ���
	this._control = function(child)
	{
		var self =child;
		Interval.clear(self._interval);
		if (self._last_state == false)
		{
			if (typeof(self._parent._openning) == 'object')
				{
					self._parent._openning._off();
					self._parent._openning = self;
				}
			self._on();
			return false;
		}
		else
		{
			//self._off();
			return false;
		}
	}
	
}//Navbar�����


//===============================interval ����=============================
//ע�⣺_stack ֻ��20��
//����ʱ���븳��ֵ1-n
Interval=
{
	length:20,
	_action : new Array(length),
	_stack : new Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19),
	_interval : Array(length),
	_timeout: new Array(length),
	//for(var i=0;i<_action.length;i++)stack.push(i);,
	set:function(action_function,speed,time_out)
	{
		time_out = time_out?time_out:15000;//Ĭ�ϵ�interval��ʱΪ15000�룬�������Ҫ���ó�ʱ����ô�������setTimeout ������ע�͵�; 
 		var p = Interval._stack.pop();
		if(p)
		{
			Interval._action[p] = action_function;
			Interval._interval[p]=setInterval('if(Interval._action['+p+'])Interval._action['+p+']();',speed);//������ظ�ִ�к�������д��'Interval._action['+p+']'��Ϊ�ܿ���Interval.clear�Ժ󣬻���һ��û��ִ����ϣ����ǾͲ�����һ�δ���
			Interval._timeout[p] = setTimeout('Interval.clear('+p+')',time_out);//��������interval��ʱ,�������Ҫ��ע�͵�;
			return p;
		}
	},
	clear:function(p)
	{
		if (Interval._action[p])
		{
			clearInterval(Interval._interval[p]);
			clearTimeout(Interval._timeout[p]);//�������interval��ʱ,���û�����ó�ʱ��ע�͵�;
			Interval._action[p] = "";
			Interval._stack.push(p);
		}
	}
}
//Interval �������